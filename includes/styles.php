<?php
function ccbpress_plugin_styles() {
    wp_enqueue_style( 'ccbpress-core-display', CCBPRESS_CORE_PLUGIN_URL . 'assets/css/display.css' );
    wp_enqueue_style( 'ccbpress-core', CCBPRESS_CORE_PLUGIN_URL . 'assets/css/ccbpress.css' );
    wp_enqueue_script( 'ccbpress-core', CCBPRESS_CORE_PLUGIN_URL . 'assets/js/ccbpress.js', array('jquery') );
    wp_register_style( 'featherlight', CCBPRESS_CORE_PLUGIN_URL . 'lib/featherlight/featherlight.min.css', array('dashicons'), '1.7.13' );
    wp_register_script( 'featherlight', CCBPRESS_CORE_PLUGIN_URL . 'lib/featherlight/featherlight.min.js', array('jquery'), '1.7.13' );
}
add_action( 'wp_enqueue_scripts', 'ccbpress_plugin_styles' );
add_action( 'customize_controls_enqueue_scripts', 'ccbpress_plugin_styles' );
