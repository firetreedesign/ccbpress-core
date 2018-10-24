<?php
function ccbpress_plugin_styles() {
    wp_enqueue_style( 'ccbpress-core-display', CCBPRESS_CORE_PLUGIN_URL . 'assets/css/display.css' );
    wp_enqueue_style( 'ccbpress-core', CCBPRESS_CORE_PLUGIN_URL . 'assets/css/ccbpress.css' );
    
    wp_register_style( 'lity', CCBPRESS_CORE_PLUGIN_URL . 'lib/lity/dist/lity.css', array(), '2.3.1' );
    wp_register_script( 'lity', CCBPRESS_CORE_PLUGIN_URL . 'lib/lity/dist/lity.js', array('jquery'), '2.3.1' );
}
add_action( 'wp_enqueue_scripts', 'ccbpress_plugin_styles' );
add_action( 'customize_controls_enqueue_scripts', 'ccbpress_plugin_styles' );
