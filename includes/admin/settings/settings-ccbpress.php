<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class CCBPress_Settings_CCBPress extends CCBPress_Settings {

    public function __construct() {
		add_action( 'admin_init', array( $this, 'initialize' ) );
    }

    public function initialize() {

        // If the option does not exist, then add it
    	if ( false == get_option( 'ccbpress_settings' ) ) {
    		add_option( 'ccbpress_settings' );
    	}

        // First, we register a section. This is necessary since all future options must belong to one.
    	add_settings_section(
    		'ccbpress_settings_uninstall_section',
    		__( 'Uninstall', 'ccbpress-core' ),
    		array( $this, 'ccbpress_uninstall_section_callback' ),
    		'ccbpress_settings'
    	);

        // The Remove Data field
    	add_settings_field(
    		'remove_data',
    		'<strong>' . __('Church Data Connect for Church Community Builder', 'ccbpress-core') . '</strong>',
    		array( $this, 'checkbox_callback' ),
    		'ccbpress_settings',
    		'ccbpress_settings_uninstall_section',
    		array(
    			'field_id'  => 'remove_data',
    			'page_id'   => 'ccbpress_settings',
    			'label'     => __('Remove all of its data when the plugin is deleted.', 'ccbpress-core'),
    		)
    	);

		$uninstall_settings = apply_filters( 'ccbpress_uninstall_settings', array() );
		foreach ( $uninstall_settings as $setting ) {
			add_settings_field(
	    		$setting['id'] . '_remove_data',
	    		'<strong>' . $setting['name'] . '</strong>',
	    		array( $this, 'checkbox_callback' ),
	    		'ccbpress_settings',
	    		'ccbpress_settings_uninstall_section',
	    		array(
	    			'field_id'  => $setting['id'] . '_remove_data',
	    			'page_id'   => 'ccbpress_settings',
					'label'		=> __( 'Remove all of its data when the plugin is deleted.', 'ccbpress-core' ),
	    		)
	    	);
		}

        // Finally, we register the fields with WordPress
    	register_setting(
    		'ccbpress_settings',			// The group name of the settings being registered
    		'ccbpress_settings',			// The name of the set of options being registered
    		array( $this, 'sanitize_callback' )	// The name of the function responsible for validating the fields
    	);

    }

    public function ccbpress_uninstall_section_callback() {
        echo '<p>' . __('Upon deletion of Church Data Connect for Church Community Builder, you can optionally remove any custom tables, settings, and license keys that have been entered.', 'ccbpress-core') . '</p>';
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
new CCBPress_Settings_CCBPress();
