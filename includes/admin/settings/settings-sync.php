<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class CCBPress_Settings_Sync extends CCBPress_Settings {

    public function __construct() {
		add_action( 'admin_init', array( $this, 'initialize' ) );
    }

    public function initialize() {

		if ( ! CCBPress()->ccb->is_connected() ) {
			return;
		}

        // If the option does not exist, then add it
    	if ( false == get_option( 'ccbpress_settings_sync' ) ) {
    		add_option( 'ccbpress_settings_sync' );
    	}

        // Get the array of registered services
		$services = apply_filters( 'ccbpress_ccb_services', array() );

        // First, we register a section. This is necessary since all future options must belong to one.
    	add_settings_section(
    		'ccbpress_settings_group_sync_section',
    		__( 'Group Data', 'ccbpress-core' ),
    		array( $this, 'group_sync_section_callback' ),
    		'ccbpress_settings_sync'
    	);

		// Set some default values
		$group_auto_sync = FALSE;
		$group_auto_sync_schedule = __('No services are currently registered to use groups.', 'ccbpress-core');

		// Check for group related services
		if ( in_array( 'group_profiles', $services ) || in_array( 'group_profile_from_id', $services ) ) {
			$group_auto_sync_schedule = __('Scheduled to run in approximately ', 'ccbpress-core') . human_time_diff( strtotime('now'), wp_next_scheduled( 'ccbpress_daily_maintenance' ) );
			$group_auto_sync = TRUE;
		}

        /**
         * Automatic Sync
         */

        add_settings_field(
            'group_auto_sync',
            '<strong>' . __('Automatic Import', 'ccbpress-core') . '</strong>',
            array( $this, 'text_callback' ),
            'ccbpress_settings_sync',
            'ccbpress_settings_group_sync_section',
            array(
                'header' => NULL,
                'title' => NULL,
                'content' => $group_auto_sync_schedule,
            )
        );

        if ( TRUE === $group_auto_sync ) {

    		/**
        	 * Last Group Sync
        	 */

    		$last_group_sync = get_option( 'ccbpress_last_group_sync', 'Never' );
     		if ( 'Never' != $last_group_sync ) {
    			$last_group_sync = human_time_diff( strtotime('now', current_time('timestamp') ), strtotime( $last_group_sync, current_time('timestamp') ) ) . ' ago';
     		}

        	add_settings_field(
        		'group_last_sync',
        		'<strong>' . __('Last Import', 'ccbpress-core') . '</strong>',
        		array( $this, 'text_callback' ),
        		'ccbpress_settings_sync',
        		'ccbpress_settings_group_sync_section',
    			array(
                    'header' => NULL,
                    'title' => NULL,
                    'content' => '<span class="ccbpress-last-group-sync">' . $last_group_sync . '</span>',
                )
        	);

    		// Include Images
        	add_settings_field(
        		'group_include_images',
        		'<strong>' . __('Include images?', 'ccbpress-core') . '</strong>',
        		array( $this, 'checkbox_callback' ),
        		'ccbpress_settings_sync',
        		'ccbpress_settings_group_sync_section',
        		array(
        			'field_id'  => 'group_include_images',
        			'page_id'   => 'ccbpress_settings_sync',
        			'label'     => __('Download group images during import. <i>(Turning this on will dramatically slow down the process.)</i>', 'ccbpress-core'),
        		)
        	);

    		/**
        	 * Manual Sync
        	 */

    		$group_sync_status = get_option( 'ccbpress_group_sync_in_progress', false );
    		if ( $group_sync_status ) {
    			$group_sync_status = 'running';
    		}

        	add_settings_field(
        		'group_manual_sync',
        		'<strong>' . __('Manual Import', 'ccbpress-core') . '</strong>',
        		array( $this, 'text_callback' ),
        		'ccbpress_settings_sync',
        		'ccbpress_settings_group_sync_section',
    			array(
                    'header' => NULL,
                    'title' => NULL,
                    'content' => '<button class="button button-secondary" id="ccbpress-manual-group-sync-button" data-ccbpress-status="' . $group_sync_status . '">Run Import Now</button><div id="ccbpress-group-sync-status"></div>',
                )
        	);

        }

        // First, we register a section. This is necessary since all future options must belong to one.
    	add_settings_section(
    		'ccbpress_settings_event_sync_section',
    		__( 'Event Data', 'ccbpress-core' ),
    		array( $this, 'event_sync_section_callback' ),
    		'ccbpress_settings_sync'
    	);

		// Set some default values
		$event_auto_sync = FALSE;
		$event_auto_sync_schedule = __('No services are currently registered to use events.', 'ccbpress-core');

		// Check for group related services
		if ( in_array( 'event_profiles', $services ) || in_array( 'event_profile_from_id', $services ) ) {
			$event_auto_sync_schedule = __('Scheduled to run in approximately ', 'ccbpress-core') . human_time_diff( strtotime('now'), wp_next_scheduled( 'ccbpress_daily_maintenance' ) );
			$event_auto_sync = TRUE;
		}

		/**
    	 * Automatic Sync
    	 */

		add_settings_field(
    		'event_auto_sync',
    		'<strong>' . __('Automatic Import', 'ccbpress-core') . '</strong>',
    		array( $this, 'text_callback' ),
    		'ccbpress_settings_sync',
    		'ccbpress_settings_event_sync_section',
			array(
                'header' => NULL,
                'title' => NULL,
                'content' => $event_auto_sync_schedule,
            )
    	);

        if ( TRUE === $event_auto_sync ) {

    		/**
        	 * Last Event Sync
        	 */

    		$last_event_sync = get_option( 'ccbpress_last_event_sync', 'Never' );
     		if ( 'Never' != $last_event_sync ) {
    			$last_event_sync = human_time_diff( strtotime('now', current_time('timestamp') ), strtotime( $last_event_sync, current_time('timestamp') ) ) . ' ago';
     		}

        	add_settings_field(
        		'event_last_sync',
        		'<strong>' . __('Last Import', 'ccbpress-core') . '</strong>',
        		array( $this, 'text_callback' ),
        		'ccbpress_settings_sync',
        		'ccbpress_settings_event_sync_section',
    			array(
                    'header' => NULL,
                    'title' => NULL,
                    'content' => '<span class="ccbpress-last-event-sync">' . $last_event_sync . '</span>',
                )
        	);

    		/**
        	 * Manual Sync
        	 */

    		$event_sync_status = get_option( 'ccbpress_event_sync_in_progress', false );
    		if ( $event_sync_status ) {
    			$event_sync_status = 'running';
    		}

        	add_settings_field(
        		'event_manual_sync',
        		'<strong>' . __('Manual Import', 'ccbpress-core') . '</strong>',
        		array( $this, 'text_callback' ),
        		'ccbpress_settings_sync',
        		'ccbpress_settings_event_sync_section',
    			array(
                    'header' => NULL,
                    'title' => NULL,
                    'content' => '<button class="button button-secondary" id="ccbpress-manual-event-sync-button" data-ccbpress-status="' . $event_sync_status . '">Run Import Now</button><div id="ccbpress-event-sync-status"></div>',
                )
        	);

        }

        // Finally, we register the fields with WordPress
    	register_setting(
    		'ccbpress_settings_sync',			// The group name of the settings being registered
    		'ccbpress_sync',			// The name of the set of options being registered
    		array( $this, 'sanitize_callback' )	// The name of the function responsible for validating the fields
    	);

    }

    public function group_sync_section_callback() {
        echo '<p>' . __('Here you can manage the import settings for you Church Community Builder group data. If you have add-ons that need group data, we will automatically import the data from CCB for you every night.', 'ccbpress-core') . '</p>';
	}

    public function event_sync_section_callback() {
        echo '<p>' . __('Here you can manage the import settings for you Church Community Builder event data. If you have add-ons that need event data, we will automatically import the data from CCB for you every night.', 'ccbpress-core') . '</p>';
	}

	public function sanitize_callback( $input ) {

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
new CCBPress_Settings_Sync();
