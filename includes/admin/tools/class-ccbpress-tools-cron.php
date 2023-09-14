<?php
/**
 * Admin - Tools - Cron Scheduler
 *
 * @since 1.5
 * @package CCBPress_Core
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CCBPress_Tools_Cron class
 *
 * @since 1.5
 */
class CCBPress_Tools_Cron extends CCBPress_Settings {

	/**
	 * Construct
	 *
	 * @since 1.5
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'initialize' ) );
	}

	/**
	 * Initialize
	 *
	 * @since 1.5
	 *
	 * @return void
	 */
	public function initialize() {

		// First, we register a section. This is necessary since all future options must belong to one.
		add_settings_section(
			'ccbpress_tools_cron_section',
			__( 'Cron Scheduler', 'ccbpress-core' ),
			array( $this, 'cron_section_callback' ),
			'ccbpress_tools_cron'
		);

		add_settings_field(
			'reschedule_cron_jobs',
			'<strong>' . __( 'Reschedule Cron Jobs', 'ccbpress-core' ) . '</strong>',
			array( $this, 'text_callback' ),
			'ccbpress_tools_cron',
			'ccbpress_tools_cron_section',
			array(
				'header'  => null,
				'title'   => null,
				'content' => '<a class="button" id="ccbpress-reschedule-cron-jobs-button">Reschedule Cron Jobs</a>',
			)
		);

		// Finally, we register the fields with WordPress.
		register_setting(
			'ccbpress_tools_cron', // The group name of the settings being registered.
			'ccbpress_tools_cron', // The name of the set of options being registered.
			array( $this, 'sanitize_callback' ) // The name of the function responsible for validating the fields.
		);
	}

	/**
	 * Cron Section Callback
	 *
	 * @return void
	 */
	public function cron_section_callback() {
		echo sprintf( '<p>%s</p>', esc_html__( 'If you are having issues with data updating, you can use the tool below to reschedule the cron job that updates your data.', 'ccbpress-core' ) );
	}

	/**
	 * Sanitize Callback
	 *
	 * @param array $input Input array.
	 * @return array
	 */
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
new CCBPress_Tools_Cron();
