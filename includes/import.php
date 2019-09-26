<?php
/**
 * CCBPress - Import
 *
 * @since 1.0.3
 * @package CCBPress Core
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CCBPress Import class
 */
class CCBPress_Import {

	/**
	 * Initialize the class
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'ccbpress_import', __CLASS__ . '::run' );
		add_action( 'ccbpress_import_job_queued', __CLASS__ . '::import_job_queued' );
		add_action( 'ccbpress_import_jobs_dispatched', __CLASS__ . '::import_jobs_dispatched' );
		add_action( 'ccbpress_background_get_complete', __CLASS__ . '::import_complete' );
		add_action( 'ccbpress_maintenance', __CLASS__ . '::find_stalled_imports' );
	}

	/**
	 * Reset the impor status
	 *
	 * @since 1.1.12
	 *
	 * @return void
	 */
	public static function reset() {
		delete_option( 'ccbpress_last_import' );
		delete_option( 'ccbpress_import_in_progress' );
	}

	/**
	 * Reschedule the import job
	 *
	 * @since 1.1.12
	 *
	 * @return void
	 */
	public static function reschedule() {
		if ( false === wp_next_scheduled( 'ccbpress_import' ) ) {
			wp_schedule_single_event( time() + 3600, 'ccbpress_import' );
		}
	}

	/**
	 * Run the import
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function run() {

		delete_option( 'ccbpress_cancel_import' );

		if ( ! CCBPress()->ccb->is_connected() ) {
			self::reset();
			self::reschedule();
			return;
		}

		$jobs = apply_filters( 'ccbpress_import_jobs', array() );

		if ( ! is_array( $jobs ) ) {
			self::reset();
			self::reschedule();
			return;
		}

		if ( 0 === count( $jobs ) ) {
			self::reset();
			self::reschedule();
			return;
		}

		// Make sure that the queue is empty before scheduling another import.
		if ( ! self::is_queue_empty() ) {
			self::find_stalled_imports();
			return;
		}

		update_option( 'ccbpress_current_import', date( 'Y-m-d H:i:s', time() ) );

		foreach ( $jobs as $job ) {
			do_action( 'ccbpress_import_job_queued', $job );
			CCBPress()->get->push_to_queue( $job )->save();
		}

		wp_clear_scheduled_hook( 'ccbpress_import' );

		do_action( 'ccbpress_import_jobs_dispatched' );
		CCBPress()->get->dispatch();

	}

	/**
	 * Import job queued
	 *
	 * @param array $job Job array.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function import_job_queued( $job ) {
		update_option( 'ccbpress_import_in_progress', __( 'Pushing job to the queue...', 'ccbpress-core' ) );
	}

	/**
	 * Import job dispatched
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function import_jobs_dispatched() {
		update_option( 'ccbpress_import_in_progress', __( 'Import job has been dispatched...', 'ccbpress-core' ) );
	}

	/**
	 * Import complete
	 *
	 * @since 1.0.3
	 *
	 * @return void
	 */
	public static function import_complete() {

		/**
		 * Update our import status
		 */
		delete_option( 'ccbpress_import_in_progress' );

		if ( 'yes' !== get_option( 'ccbpress_cancel_import', 'no' ) ) {
			update_option( 'ccbpress_last_import', get_option( 'ccbpress_current_import' ) );
		}

		delete_option( 'ccbpress_current_import' );

		/**
		 * Re-schedule the import job
		 */
		self::reschedule();

	}

	/**
	 * Check if the queue is empty
	 *
	 * @since 1.3.2
	 *
	 * @return boolean
	 */
	public static function is_queue_empty() {

		global $wpdb;

		$table  = $wpdb->options;
		$column = 'option_name';

		// if ( is_multisite() ) {
		// 	$table  = $wpdb->sitemeta;
		// 	$column = 'meta_key';
		// }

		$key   = $wpdb->esc_like( 'wp_ccbpress_get_batch_' ) . '%';
		$count = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*)
				FROM {$table}
				WHERE {$column} LIKE %s",
				$key
			)
		);

		return ( $count > 0 ) ? false : true;

	}

	/**
	 * Find stalled imports
	 *
	 * @return void
	 */
	public static function find_stalled_imports() {
		$is_stalled       = false;
		$in_progress      = false;
		$job_cron_exists  = false;
		$job_queue_exists = ! self::is_queue_empty();

		if ( false !== get_option( 'ccbpress_import_in_progress', false ) ) {
			$in_progress = true;
		}

		if ( false !== wp_next_scheduled( 'wp_ccbpress_get_cron' ) ) {
			$job_cron_exists = true;
		}

		// Check if there are jobs in the queue, but the CRON to run them does not exist.
		if ( true === $job_queue_exists && false === $job_cron_exists ) {
			$is_stalled = true;
		}

		// Check if the "in_progress" option exists, but there are no jobs in the queue.
		if ( true === $in_progress && false === $job_queue_exists ) {
			$is_stalled = true;
		}

		// Check if the "in_progress" option exists, but the CRON to run it does not exist.
		if ( true === $in_progress && false === $job_cron_exists ) {
			$is_stalled = true;
		}

		// If the import is not stalled, then exit.
		if ( false === $is_stalled ) {
			return;
		}

		// Run the task to clean up the stalled import.
		self::cleanup_stalled_import();
	}

	/**
	 * Cleanup the stalled import
	 *
	 * @return void
	 */
	private static function cleanup_stalled_import() {
		global $wpdb;

		$key = $wpdb->esc_like( 'wp_ccbpress_get_batch_' ) . '%';
		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM $wpdb->options WHERE option_name LIKE %s",
				$key
			)
		);

		delete_option( 'ccbpress_import_in_progress' );
		delete_option( 'ccbpress_current_import' );
		delete_option( 'ccbpress_cancel_import' );
		delete_site_transient( 'wp_ccbpress_get_process_lock' );

		CCBPress_Core::schedule_cron();
	}

}
CCBPress_Import::init();
