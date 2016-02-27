<?php
function ccbpress_admin_scripts() {
    wp_enqueue_script( 'ccbpress-core-admin', CCBPRESS_CORE_PLUGIN_URL . 'assets/js/admin/admin.js' );
	wp_register_script( 'ccbpress-select2', CCBPRESS_CORE_PLUGIN_URL . 'lib/select2ccbpress/select2ccbpress.full.min.js', array('jquery'), '4.0.1' );
	wp_localize_script( 'ccbpress-core-admin', 'ccbpress_vars', array(
		'ccbpress_nonce' => wp_create_nonce( 'ccbpress-nonce' )
	) );

	if ( isset( $_GET['page'] ) && isset( $_GET['tab'] ) && 'ccbpress-settings' == $_GET['page'] && 'sync' == $_GET['tab'] ) {
		wp_enqueue_script( 'ccbpress-core-group-sync', CCBPRESS_CORE_PLUGIN_URL . 'assets/js/admin/group-sync.js' );
        wp_enqueue_script( 'ccbpress-core-event-sync', CCBPRESS_CORE_PLUGIN_URL . 'assets/js/admin/event-sync.js' );
	}
}
add_action( 'admin_enqueue_scripts', 'ccbpress_admin_scripts' );
