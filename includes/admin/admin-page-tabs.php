<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class CCBPress_Admin_Page_Tabs {

	public function __construct() {
		add_filter( 'ccbpress_settings_page_tabs', array( $this, 'settings_page_tabs_late' ), 100 );
		add_filter( 'ccbpress_settings_page_tabs', array( $this, 'settings_page_tabs' ) );
		add_filter( 'ccbpress_settings_page_actions', array( $this, 'settings_page_actions' ) );
	}

	public function settings_page_tabs( $tabs ) {

		$tabs[] = array(
			'tab_id'		=> 'ccbpress',
			'settings_id'	=> 'ccbpress_settings',
			'title'			=> __('CCBPress', 'ccbpress-core'),
			'submit'		=> TRUE,
		);

		$tabs[] = array(
			'tab_id'		=> 'ccb',
			'settings_id'	=> 'ccbpress_settings_ccb',
			'title'			=> __('Church Community Builder', 'ccbpress-core'),
			'submit'		=> TRUE,
		);

		if ( CCBPress()->ccb->is_connected() ) {

			$tabs[] = array(
				'tab_id'			=> 'sync',
				'settings_id'	=> 'ccbpress_settings_sync',
				'title'			=> __('Data Import', 'ccbpress-core'),
				'submit'		=> TRUE,
			);

		}

		return $tabs;

	}

	public function settings_page_tabs_late( $tabs ) {

		if ( has_filter('ccbpress_license_keys') ) {
			$tabs[] = array(
				'tab_id'		=> 'licenses',
				'settings_id'	=> 'ccbpress_settings_licenses',
				'title'			=> __('Licenses', 'ccbpress-core'),
				'submit'		=> TRUE,
			);
		}

		return $tabs;

	}

	public function settings_page_actions( $actions ) {

		$actions[] = array(
			'tab_id'	=> 'ccb',
			'type'		=> 'primary',
			'class'		=> NULL,
			'link'		=> 'http://support.churchcommunitybuilder.com/customer/portal/articles/640595',
			'target'	=> '_blank',
			'title'		=> __('How to create an API user', 'ccbpress-core') . ' <span class="dashicons dashicons-external" style="vertical-align: text-bottom;"></span>',
		);

		$actions[] = array(
			'tab_id'	=> 'ccb',
			'type'		=> 'secondary',
			'class'		=> 'ccbpress-required-services',
			'link'		=> '#',
			'target'	=> NULL,
			'title'		=> '<span class="dashicons dashicons-info" style="vertical-align: text-bottom;"></span> ' . __('Required services', 'ccbpress-core'),
		);

		return $actions;

	}

}
new CCBPress_Admin_Page_Tabs();
