<?php
function ccbpress_plugin_scripts() {
	wp_register_script( 'ccbpress-core-beacon', CCBPRESS_CORE_PLUGIN_URL . 'assets/js/beacon.js', array(), '1.0.0', true );

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
add_action( 'admin_enqueue_scripts', 'ccbpress_plugin_scripts' );
