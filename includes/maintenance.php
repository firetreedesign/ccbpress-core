<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class CCBPress_Maintenance {

	public function init() {

		if ( $this->should_sync('groups') ) {
			$this->run_sync('groups');
		}

	}

	private function should_sync( $what ) {

		// First check that we are connected to CCB
		if ( ! CCBPress()->ccb->is_connected() ) {
			return FALSE;
		}

		switch( $what ) {

			case 'groups':
				// Get the array of registered services
				$services = apply_filters( 'ccbpress_ccb_services', array() );
				// Check for group related services
				if ( in_array( 'group_profiles', $services ) || in_array( 'group_profile_from_id', $services ) ) {
					return TRUE;
				}
				break;

		}

		return FALSE;

	}

	private function run_sync( $what ) {

		$ccbpress_sync_options = get_option('ccbpress_settings_sync', array() );

		switch( $what ) {

			case 'groups':

				$include_image_link = '0';
				if ( isset( $ccbpress_sync_options['group_include_images'] ) ) {
					$include_image_link = '1';
				}

				CCBPress()->sync->push_to_queue( array(
					'srv' => 'group_profiles',
					'args' => array(
						'include_participants'	=> '0',
						'include_image_link'	=> $include_image_link,
						'page'					=> 1,
						'per_page'				=> 100,
						'cache_lifespan'		=> 0,
					),
				) );
				CCBPress()->sync->save()->dispatch();
				break;

		}

	}

}

add_action('ccbpress_daily_maintenance', array( 'CCBPress_Maintenance', 'init' ) );
