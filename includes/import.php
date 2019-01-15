<?php
/**
 * CCBPress - Import
 *
 * @since	1.0.3
 * @package	CCBPress Core
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
		add_action( 'ccbpress_import',					__CLASS__ . '::run' );
		add_action( 'ccbpress_import_job_queued',		__CLASS__ . '::import_job_queued' );
		add_action( 'ccbpress_import_jobs_dispatched',	__CLASS__ . '::import_jobs_dispatched' );
		add_action( 'ccbpress_background_get_complete', __CLASS__ . '::import_complete' );
		add_action( 'wp_ajax_ccbpress_import',			__CLASS__ . '::ajax_run' );
		add_action( 'wp_ajax_ccbpress_import_status',	__CLASS__ . '::ajax_status' );
		add_action( 'wp_ajax_ccbpress_last_import',		__CLASS__ . '::ajax_last_import' );
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
			self::reset();
			self::reschedule();
			return;
		}

		update_option( 'ccbpress_current_import', date( 'Y-m-d H:i:s', current_time( 'timestamp' ) ) );

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
	 * Run the import via ajax
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function ajax_run() {

		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'ccbpress-nonce' ) ) {
			die( esc_html__( 'Insufficient Permissions', 'ccbpress-core' ) );
		}

		self::run();

		wp_send_json( 'started' );

	}

	/**
	 * Get the status via ajax
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function ajax_status() {

		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'ccbpress-nonce' ) ) {
			die( esc_html__( 'Insufficient Permissions', 'ccbpress-core' ) );
		}

		$status = array();
		$progress = get_option( 'ccbpress_import_in_progress', false );

		if ( false === $progress ) {
			wp_send_json( 'false' );
		}

		array_push( $status, array(
			'text' => $progress,
			'element'	=> 'strong',
		) );
		array_push( $status, array(
			'text'		=> esc_html__( 'Import is running in the background. Leaving this page will not interrupt the process.', 'ccbpress-core' ),
			'element'	=> 'i',
		) );

		wp_send_json( $status );
	}

	/**
	 * Get the last import via ajax
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function ajax_last_import() {

		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'ccbpress-nonce' ) ) {
			die( esc_html__( 'Insufficient Permissions', 'ccbpress-core' ) );
		}

		$last_import = get_option( 'ccbpress_last_import', 'Never' );
		if ( 'Never' === $last_import ) {
			wp_send_json( $last_import );
		} else {
			wp_send_json( esc_html( human_time_diff( strtotime( 'now', current_time( 'timestamp' ) ), strtotime( $last_import, current_time( 'timestamp' ) ) ) . ' ago' ) );
		}
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
		
		if ( is_multisite() ) {
			$table  = $wpdb->sitemeta;
			$column = 'meta_key';
		}
		
		$key = $wpdb->esc_like( 'wp_ccbpress_get_batch_' ) . '%';
		$count = $wpdb->get_var( $wpdb->prepare( "
			SELECT COUNT(*)
			FROM {$table}
			WHERE {$column} LIKE %s
		", $key ) );
		
		return ( $count > 0 ) ? false : true;

	}

}
CCBPress_Import::init();
