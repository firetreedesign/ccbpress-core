<?php
/**
 * Admin Page Tabs
 *
 * @package CCBPress_Core
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CCBPress Admin Page Tabs Class
 */
class CCBPress_Admin_Page_Tabs {

	/**
	 * Construct
	 */
	public function __construct() {
		add_filter( 'ccbpress_settings_page_tabs', array( $this, 'settings_page_tabs_late' ), 100 );
		add_filter( 'ccbpress_settings_page_tabs', array( $this, 'settings_page_tabs' ) );
		add_filter( 'ccbpress_settings_page_actions', array( $this, 'settings_page_actions' ) );
		add_filter( 'ccbpress_tools_page_tabs', array( $this, 'tools_page_tabs' ) );
	}

	/**
	 * Settings Page Tabs
	 *
	 * @param array $tabs Tabs array.
	 * @return array
	 */
	public function settings_page_tabs( $tabs ) {

		$tabs[] = array(
			'tab_id'      => 'ccb',
			'settings_id' => 'ccbpress_settings_ccb',
			'title'       => esc_html__( 'Church Community Builder', 'ccbpress-core' ),
			'submit'      => true,
		);

		$tabs[] = array(
			'tab_id'      => 'ccbpress',
			'settings_id' => 'ccbpress_settings',
			'title'       => esc_html__( 'Options', 'ccbpress-core' ),
			'submit'      => true,
		);

		if ( CCBPress()->ccb->is_connected() ) {

			$tabs[] = array(
				'tab_id'      => 'import',
				'settings_id' => 'ccbpress_settings_import',
				'title'       => esc_html__( 'Data Import', 'ccbpress-core' ),
				'submit'      => false,
			);

		}

		return $tabs;
	}

	/**
	 * Settings Page Tabs Late
	 *
	 * @param array $tabs Tabs array.
	 * @return array
	 */
	public function settings_page_tabs_late( $tabs ) {

		if ( has_filter( 'ccbpress_license_keys' ) ) {
			$tabs[] = array(
				'tab_id'      => 'licenses',
				'settings_id' => 'ccbpress_settings_licenses',
				'title'       => esc_html__( 'Licenses', 'ccbpress-core' ),
				'submit'      => true,
			);
		}

		return $tabs;
	}

	/**
	 * Settings Page Actions
	 *
	 * @param array $actions Actions array.
	 * @return array
	 */
	public function settings_page_actions( $actions ) {

		$actions[] = array(
			'tab_id' => 'ccb',
			'class'  => null,
			'link'   => 'http://support.churchcommunitybuilder.com/customer/portal/articles/640595',
			'target' => '_blank',
			'title'  => esc_html__( 'How to create an API user', 'ccbpress-core' ),
		);

		$actions[] = array(
			'tab_id' => 'ccb',
			'class'  => 'ccbpress-required-services',
			'link'   => '#',
			'target' => null,
			'title'  => esc_html__( 'Required services', 'ccbpress-core' ),
		);

		return $actions;
	}

	/**
	 * Tools Page Tabs
	 *
	 * @param array $tabs Tabs array.
	 * @return array
	 */
	public function tools_page_tabs( $tabs ) {

		if ( CCBPress()->ccb->is_connected() ) {

			$tabs[] = array(
				'tab_id'      => 'cache',
				'settings_id' => 'ccbpress_tools_cache',
				'title'       => esc_html__( 'Cache', 'ccbpress-core' ),
				'submit'      => false,
			);

			$tabs[] = array(
				'tab_id'      => 'cron',
				'settings_id' => 'ccbpress_tools_cron',
				'title'       => esc_html__( 'Cron Scheduler', 'ccbpress-core' ),
				'submit'      => false,
			);

		}

		return $tabs;
	}
}
new CCBPress_Admin_Page_Tabs();
