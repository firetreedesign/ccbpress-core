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
		wp_register_script( 'ccbpress-core-beacon', CCBPRESS_CORE_PLUGIN_URL . 'assets/js/help.js', array(), '1.0.1', true );
	}

	/**
	 * Localize Scripts
	 *
	 * @return void
	 */
	private function localize() {
		wp_localize_script( 'ccbpress-core-admin', 'ccbpress_vars', array(
			'nonce' => wp_create_nonce( 'ccbpress-nonce' ),
			'reset_import_dialog' => __( 'Are you sure that you want to reset the last import time?', 'ccbpress-core' ),
		) );

		$current_user = wp_get_current_user();
		wp_localize_script( 'ccbpress-core-beacon', 'ccbpress_core_beacon_vars', array(
			'customer_name'		=> $current_user->display_name,
			'customer_email'	=> $current_user->user_email,
			'ccbpress_ver'		=> CCBPress()->version,
			'wp_ver'			=> get_bloginfo( 'version' ),
			'php_ver'			=> phpversion(),
			'topics'			=> apply_filters( 'ccbpress_support_topics', array(
				array(
				   'val'	=> 'general',
				   'label'	=> __( 'General question', 'ccbpress-core' ),
				),
				array(
				   'val'	=> 'ccb-credentials',
				   'label'	=> __( 'Help connecting to Church Community Builder', 'ccbpress-core' ),
				),
				array(
				   'val'	=> 'bug',
				   'label'	=> __( 'I think I found a bug', 'ccbpress-core' ),
				),
		   	) ),
	   	) );
	}

}
new CCBPress_Admin_Scripts();
