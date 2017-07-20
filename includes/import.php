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
		add_action( 'ccbpress_maintenance',				__CLASS__ . '::run' );
		add_action( 'ccbpress_import_job_queued',		__CLASS__ . '::import_job_queued' );
		add_action( 'ccbpress_import_jobs_dispatched',	__CLASS__ . '::import_jobs_dispatched' );
		add_action( 'ccbpress_background_get_complete', __CLASS__ . '::import_complete' );
		add_action( 'wp_ajax_ccbpress_import',			__CLASS__ . '::ajax_run' );
		add_action( 'wp_ajax_ccbpress_import_status',	__CLASS__ . '::ajax_status' );
		add_action( 'wp_ajax_ccbpress_last_import',		__CLASS__ . '::ajax_last_import' );

	}

	/**
	 * Run the import
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function run() {

		if ( ! CCBPress()->ccb->is_connected() ) {
			delete_option( 'ccbpress_last_import' );
			delete_option( 'ccbpress_import_in_progress' );
			return;
		}

		$jobs = apply_filters( 'ccbpress_import_jobs', array() );

		if ( ! is_array( $jobs ) ) {
			return;
		}

		if ( 0 === count( $jobs ) ) {
			return;
		}

		foreach ( $jobs as $job ) {
			do_action( 'ccbpress_import_job_queued', $job );
			CCBPress()->get->push_to_queue( $job );
		}

		do_action( 'ccbpress_import_jobs_dispatched' );
		CCBPress()->get->save()->dispatch();

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
		update_option( 'ccbpress_last_import', date( 'Y-m-d H:i:s', current_time( 'timestamp' ) ) );

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

		echo 'started';
		wp_die();

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
			echo esc_html( $last_import );
		} else {
			echo esc_html( human_time_diff( strtotime( 'now', current_time( 'timestamp' ) ), strtotime( $last_import, current_time( 'timestamp' ) ) ) . ' ago' );
		}

		wp_die();

	}

}
CCBPress_Import::init();
