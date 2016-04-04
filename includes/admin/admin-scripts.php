<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class CCBPress_Admin_Scripts {

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	public function admin_enqueue_scripts() {
		$this->enqueue();
		$this->register();
		$this->localize();
	}

	private function enqueue() {
		wp_enqueue_script( 'ccbpress-core-admin', CCBPRESS_CORE_PLUGIN_URL . 'assets/js/admin/admin.js' );

		if ( isset( $_GET['page'] ) && isset( $_GET['tab'] ) && 'ccbpress-settings' == $_GET['page'] && 'sync' == $_GET['tab'] ) {
			wp_enqueue_script( 'ccbpress-core-group-sync', CCBPRESS_CORE_PLUGIN_URL . 'assets/js/admin/group-sync.js' );
	        wp_enqueue_script( 'ccbpress-core-event-sync', CCBPRESS_CORE_PLUGIN_URL . 'assets/js/admin/event-sync.js' );
		}
	}

	private function register() {
		wp_register_script( 'ccbpress-select2', CCBPRESS_CORE_PLUGIN_URL . 'lib/select2ccbpress/select2ccbpress.full.min.js', array('jquery'), '4.0.1' );
		wp_register_script( 'ccbpress-core-beacon', CCBPRESS_CORE_PLUGIN_URL . 'assets/js/beacon.js', array(), '1.0.0', true );
	}

	private function localize() {
		wp_localize_script( 'ccbpress-core-admin', 'ccbpress_vars', array(
			'ccbpress_nonce' => wp_create_nonce( 'ccbpress-nonce' )
		) );

		$current_user = wp_get_current_user();
		wp_localize_script( 'ccbpress-core-beacon', 'ccbpress_core_beacon_vars', array(
		   'customer_name'	=> $current_user->display_name,
		   'customer_email'	=> $current_user->user_email,
		   'ccbpress_ver'	=> CCBPress()->version,
		   'wp_ver'			=> get_bloginfo('version'),
		   'php_ver'		=> phpversion(),
		   'topics'			=> apply_filters( 'ccbpress_support_topics', array(
			   array(
				   'val'	=> 'general',
				   'label'	=> __('General question', 'ccbpress-core'),
			   ),
			   array(
				   'val'	=> 'ccb-credentials',
				   'label'	=> __('Help connecting to Church Community Builder', 'ccbpress-core'),
			   ),
			   array(
				   'val'	=> 'bug',
				   'label'	=> __('I think I found a bug', 'ccbpress-core'),
			   )
		   ) ),
	   ) );
	}

}
new CCBPress_Admin_Scripts();
