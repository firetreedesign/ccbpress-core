<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class CCBPress_Settings_Licenses_CCB extends CCBPress_Settings {

    public function __construct() {
        add_action( 'admin_init', array( $this, 'initialize' ) );
    }

    public function initialize() {

        // First, we register a section. This is necessary since all future options must belong to one.
    	add_settings_section(
    		'ccbpress_settings_licenses_section',
    		__( 'License Keys', 'ccbpress-core' ),
    		array( $this, 'section_callback' ),
    		'ccbpress_settings_licenses'
    	);

        // If the option does not exist, then add it
    	if ( false == get_option( 'ccbpress_licenses' ) ) {
    		add_option( 'ccbpress_licenses' );
    	}

		$license_keys = apply_filters( 'ccbpress_license_keys', array() );
		foreach( $license_keys as $license ) {
			add_settings_field(
	    		$license['id'] . '_license_key',
	    		'<strong>' . $license['name'] . '</strong>',
	    		array( $this, 'license_key_callback' ),
	    		'ccbpress_settings_licenses',
	    		'ccbpress_settings_licenses_section',
	    		array(
	    			'field_id'  => $license['id'] . '_license_key',
	    			'page_id'   => 'ccbpress_licenses',
	                'size'      => 'regular',
					'label'		=> $license['notes'],
	    		)
	    	);
		}

        // Finally, we register the fields with WordPress
    	register_setting(
    		'ccbpress_settings_licenses',			// The group name of the settings being registered
    		'ccbpress_licenses',			// The name of the set of options being registered
    		array( $this, 'sanitize_callback' )	// The name of the function responsible for validating the fields
    	);

    }

    public function section_callback() {
        echo '<p>' . __('Please enter your license keys in order to receive updates and support.', 'ccbpress-core') . '</p>';
	}

    public function sanitize_callback( $input ) {

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
new CCBPress_Settings_Licenses_CCB();
