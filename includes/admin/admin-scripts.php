<?php
/**
 * CCBPress Core Admin Scripts
 *
 * @package CCBPress Core
 * @since 1.0.3
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CCBPress Admin Scripts
 *
 * @since 1.0.3
 */
class CCBPress_Admin_Scripts {

	/**
	 * Construct
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Enqueue / Register / Localize Scripts
	 *
	 * @return void
	 */
	public function admin_enqueue_scripts() {
		$this->enqueue();
		$this->register();
		$this->localize();
	}

	/**
	 * Enqueue Scripts
	 *
	 * @return void
	 */
	private function enqueue() {
		wp_enqueue_script( 'ccbpress-core-admin', CCBPRESS_CORE_PLUGIN_URL . 'assets/js/admin/admin.js' );

		if ( ! isset( $_GET['page'] ) ) {
			return;
		}

		if ( ! isset( $_GET['tab'] ) ) {
			return;
		}

		if ( 'ccbpress-settings' !== $_GET['page'] ) {
			return;
		}

		if ( 'import' !== $_GET['tab'] ) {
			return;
		}

		wp_enqueue_script( 'ccbpress-core-import', CCBPRESS_CORE_PLUGIN_URL . 'assets/js/admin/import.js' );
	}

	/**
	 * Register Scripts
	 *
	 * @return void
	 */
	private function register() {
		wp_register_script( 'chosen', CCBPRESS_CORE_PLUGIN_URL . 'lib/chosen/chosen.jquery.min.js', array( 'jquery' ), '1.7.0' );
	}

	/**
	 * Localize Scripts
	 *
	 * @return void
	 */
	private function localize() {
		wp_localize_script( 'ccbpress-core-admin', 'ccbpress_vars', array(
			'nonce' => wp_create_nonce( 'ccbpress-nonce' ),
			'messages' => array(
				'done' => __( 'Done', 'ccbpress-core' ),
				'running' => __( 'Running...', 'ccbpress-core' ),
				'manual_import_button' => __( 'Import Now', 'ccbpress-core' ),
				'reset_import_button' => __( 'Reset', 'ccbpress-core' ),
				'connection_test_button' => __( 'Check Services Now', 'ccbpress-core' ),
				'process_running' => __( 'Process is running...', 'ccbpress-core' ),
				'reset_import_confirmation' => __( 'Are you sure that you want to reset the last import time?', 'ccbpress-core' ),
			),
			'api_url' => get_rest_url(),
			'api_nonce' => wp_create_nonce( 'wp_rest' ),
		) );
	}

}
new CCBPress_Admin_Scripts();
