<?php
function ccbpress_admin_styles() {
    wp_enqueue_style( 'ccbpress-core-admin', CCBPRESS_CORE_PLUGIN_URL . 'assets/css/admin.css' );
	wp_enqueue_style( 'ccbpress-core', CCBPRESS_CORE_PLUGIN_URL . 'assets/css/ccbpress.css' );
	wp_register_style( 'ccbpress-select2', CCBPRESS_CORE_PLUGIN_URL . 'lib/select2ccbpress/select2ccbpress.min.css' );
}
add_action( 'admin_print_styles', 'ccbpress_admin_styles' );

function ccbpress_admin_customizer_styles() {
?>
	<style>
	.select2ccbpress-dropdown {
	    z-index: 510000 !important;
	}
	</style>
<?
}
add_action( 'customize_controls_print_styles', 'ccbpress_admin_customizer_styles' );
