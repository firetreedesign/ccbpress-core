<?php
/**
 * CCBPRess - Import
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
	 * Run the import
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function import_job_queued( $job ) {

		// update_option( 'ccbpress_import_in_progress', __( 'Pushing Event Calendars to the queue...', 'rockpress-events' ) );

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
			wp_send_json( $status );
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
