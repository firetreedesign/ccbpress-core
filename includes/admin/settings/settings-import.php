<?php
/**
 * Admin - Settings - Import
 *
 * @since 1.0.3
 * @package CCBPress_Core
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CCBPress_Settings_Import class
 *
 * @since 1.0.3
 */
class CCBPress_Settings_Import extends CCBPress_Settings {

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

		if ( ! CCBPress()->ccb->is_connected() ) {
			return;
		}

		// If the option does not exist, then add it.
		if ( false === get_option( 'ccbpress_settings_import', false ) ) {
			add_option( 'ccbpress_settings_import' );
		}

		// First, we register a section. This is necessary since all future options must belong to one.
		add_settings_section(
			'ccbpress_settings_import_section',
			__( 'Data Import', 'ccbpress-core' ),
			array( $this, 'data_import_section_callback' ),
			'ccbpress_settings_import'
		);

		$import_schedule = __( 'No import jobs are currently scheduled.', 'ccbpress-core' );
		$import_active = false;

		$import_jobs = apply_filters( 'ccbpress_import_jobs', array() );
		if ( 0 < count( $import_jobs ) && false !== wp_next_scheduled( 'ccbpress_import' ) ) {
			$now             = strtotime( 'now', time() );
			$next_import     = wp_next_scheduled( 'ccbpress_import' );
			$passed_due      = $now > $next_import ? '-' : '';
			$import_schedule = __( 'Scheduled to run in approximately ', 'ccbpress-core' ) . $passed_due . human_time_diff( $now, $next_import );
			$import_active   = true;
		}

		/**
		 * Automatic Sync
		 */
		add_settings_field(
			'auto_import',
			'<strong>' . __( 'Automatic Import', 'ccbpress-core' ) . '</strong>',
			array( $this, 'text_callback' ),
			'ccbpress_settings_import',
			'ccbpress_settings_import_section',
			array(
				'header'	=> null,
				'title'		=> null,
				'content'	=> $import_schedule,
			)
		);

		if ( true === $import_active || false !== get_option( 'ccbpress_import_in_progress', false ) ) {

			/**
			 * Last Import
			 */
			$last_import = get_option( 'ccbpress_last_import', 'Never' );
			if ( 'Never' !== $last_import ) {
				$last_import = human_time_diff( strtotime( 'now', time() ), strtotime( $last_import, time() ) ) . ' ago';
			}

			add_settings_field(
				'last_import',
				'<strong>' . __( 'Last Import', 'ccbpress-core' ) . '</strong>',
				array( $this, 'text_callback' ),
				'ccbpress_settings_import',
				'ccbpress_settings_import_section',
				array(
					'header'	=> null,
					'title'		=> null,
					'content'	=> '<span class="ccbpress-last-import">' . $last_import . '</span>',
				)
			);

			/**
			 * Manual Import
			 */
			$import_status = get_option( 'ccbpress_import_in_progress', false );
			if ( $import_status ) {
				$import_status = 'running';
			}

			add_settings_field(
				'import_actions',
				'<strong>' . __( 'Actions', 'ccbpress-core' ) . '</strong>',
				array( $this, 'text_callback' ),
				'ccbpress_settings_import',
				'ccbpress_settings_import_section',
				array(
					'header'	=> null,
					'title'		=> null,
					'content'	=> '<a class="button button-primary" id="ccbpress-manual-import-button" data-ccbpress-status="' . $import_status . '">Import Now</a> <a class="button" id="ccbpress-reset-import-button">Reset</a><div id="ccbpress-import-status"></div>',
				)
			);

		}

		// Finally, we register the fields with WordPress.
		register_setting(
			'ccbpress_settings_import',			// The group name of the settings being registered.
			'ccbpress_settings_import',			// The name of the set of options being registered.
			array( $this, 'sanitize_callback' )	// The name of the function responsible for validating the fields.
		);

		return;

    }

	public function data_import_section_callback() {
		echo '<p>' . __( 'Here you can manage the import settings for your Church Community Builder data. If you have add-ons that need data, we will automatically import the data from Church Community Builder on a regular schedule.', 'ccbpress-core' ) . '</p>';
	}

	public function sanitize_callback( $input ) {

		// Define all of the variables that we'll be using.
		$output = array();

		if ( ! is_array( $input ) ) {
			return $output;
		}

		// Loop through each of the incoming options.
		foreach ( $input as $key => $value ) {

			// Check to see if the current option has a value. If so, process it.
			if ( isset( $input[ $key ] ) ) {

				// Strip all HTML and PHP tags and properly handle quoted strings.
				$output[ $key ] = wp_strip_all_tags( stripslashes( $input[ $key ] ) );

			}
		}

		// Return the array.
		return $output;

	}

}
new CCBPress_Settings_Import();
