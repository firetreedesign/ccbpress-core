<?php
function ccbpress_admin_styles() {
	wp_enqueue_style( 'ccbpress-core-admin', CCBPRESS_CORE_PLUGIN_URL . 'assets/css/admin.css' );
	wp_enqueue_style( 'ccbpress-core', CCBPRESS_CORE_PLUGIN_URL . 'assets/css/ccbpress.css' );
	wp_register_style( 'chosen', CCBPRESS_CORE_PLUGIN_URL . 'lib/chosen/chosen.min.css' );
}
add_action( 'admin_enqueue_scripts', 'ccbpress_admin_styles' );
