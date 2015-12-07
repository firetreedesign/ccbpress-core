<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class CCBPress_Settings_Tools extends CCBPress_Settings {

    public function __construct() {
        add_action( 'admin_init', array( $this, 'initialize' ) );
    }

    public function initialize() {

        // First, we register a section. This is necessary since all future options must belong to one.
    	add_settings_section(
    		'ccbpress_tools_ccb_api_services_section',
    		__( 'API Services', 'ccbpress-core' ),
    		array( $this, 'ccb_section_callback' ),
    		'ccbpress_tools_ccb'
    	);

        // If the option does not exist, then add it
    	if ( false == get_option( 'ccbpress_tools_ccb' ) ) {
    		add_option( 'ccbpress_tools_ccb' );
    	}

		add_settings_field(
            'check_services_form',
            '<strong>' . __('Check Your Services', 'ccbpress-core') . '</strong>',
            array( $this, 'text_callback' ),
            'ccbpress_tools_ccb',
            'ccbpress_tools_ccb_api_services_section',
            array(
                'header' => NULL,
                'title' => NULL,
                'content' => '<button class="button button-secondary" id="ccbpress-ccb-service-check-button">Check Services Now</button> <img src="' . admin_url('/images/spinner-2x.gif') . '" width="16" height="16" class="waiting" id="ccbpress-ccb-service-check-loading" style="display: none;" /><div id="ccbpress-ccb-service-check-results"></div>',
            )
        );

        // Finally, we register the fields with WordPress
    	register_setting(
    		'ccbpress_tools_ccb',			// The group name of the settings being registered
    		'ccbpress_tools_ccb',			// The name of the set of options being registered
    		array( $this, 'ccb_sanitize_callback' )	// The name of the function responsible for validating the fields
    	);

    }

    public function ccb_section_callback() {
        echo '<p>' . __('Use this tool to check if your API User has the appropriate API Services enabled in Church Community Builder.', 'ccbpress-core') . '</p>';
	}

    public function ccb_sanitize_callback( $input ) {

        // Define all of the variables that we'll be using
    	$output = array();

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
new CCBPress_Settings_Tools();
