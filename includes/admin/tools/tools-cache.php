<?php
/**
 * Admin - Tools - Cache
 *
 * @since 1.3.4
 * @package CCBPress_Core
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CCBPress_Tools_Cache class
 *
 * @since 1.3.4
 */
class CCBPress_Tools_Cache extends CCBPress_Settings {

	/**
	 * Construct
	 *
	 * @since 1.0.3
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'initialize' ) );
	}

	/**
	 * Initialize
	 *
	 * @since 1.0.3
	 *
	 * @return void
	 */
	public function initialize() {

		// First, we register a section. This is necessary since all future options must belong to one.
		add_settings_section(
			'ccbpress_tools_cache_section',
			__( 'Purge Cache', 'ccbpress-core' ),
			array( $this, 'cache_section_callback' ),
			'ccbpress_tools_cache'
		);

        add_settings_field(
            'purge_image_cache',
            '<strong>' . __( 'Images', 'ccbpress-core' ) . '</strong>',
            array( $this, 'text_callback' ),
            'ccbpress_tools_cache',
            'ccbpress_tools_cache_section',
            array(
                'header'	=> null,
                'title'		=> null,
                'content'	=> '<a class="button" id="ccbpress-purge-image-cache-button">Purge Image Cache</a>',
            )
        );

        add_settings_field(
            'purge_transient_cache',
            '<strong>' . __( 'Transients', 'ccbpress-core' ) . '</strong>',
            array( $this, 'text_callback' ),
            'ccbpress_tools_cache',
            'ccbpress_tools_cache_section',
            array(
                'header'	=> null,
                'title'		=> null,
                'content'	=> '<a class="button" id="ccbpress-purge-transient-cache-button">Purge Transient Cache</a>',
            )
        );

		// Finally, we register the fields with WordPress.
		register_setting(
			'ccbpress_tools_cache',			// The group name of the settings being registered.
			'ccbpress_tools_cache',			// The name of the set of options being registered.
			array( $this, 'sanitize_callback' )	// The name of the function responsible for validating the fields.
		);

		return;

    }

	public function cache_section_callback() {
        echo sprintf( '<p>%s</p>', __( 'Below are some tools to use if you are having some issues with data from Church Community Builder.', 'ccbpress-core' ) );
		echo sprintf( '<p>%s</p>', __( 'Generally you will not need to use these unless instructed to by Church Data Connect for Church Community Builder support.', 'ccbpress-core' ) );
	}

	public function sanitize_callback( $input ) {

        // Define all of the variables that we'll be using
		$output = array();
		
		if ( ! is_array( $input ) ) {
			return $output;
		}

    	// Loop through each of the incoming options
    	foreach ( $input as $key => $value ) {

    		// Check to see if the current option has a value. If so, process it.
    		if ( isset( $input[$key] ) ) {

    			// Strip all HTML and PHP tags and properly handle quoted strings
    			$output[$key] = strip_tags( stripslashes( $input[$key] ) );

    		}

    	}

    	// Return the array
    	return $output;

    }

}
new CCBPress_Tools_Cache();
