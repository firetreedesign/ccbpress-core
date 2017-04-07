<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class CCBPress_Maintenance {

	public function __construct() {

		if ( $this->should_sync('groups') ) {

			switch( date('l') ) {
				case 'Sunday':
					$this->run_sync( 'groups', TRUE );
					break;
				case 'Monday':
					$this->run_sync('groups');
					$this->purge('groups');
					break;
				default:
					$this->run_sync('groups');
					break;
			}

		}

		if ( $this->should_sync('events') ) {

			switch( date('l') ) {
				case 'Sunday':
					$this->run_sync( 'events', TRUE );
					break;
				case 'Monday':
					$this->run_sync('events');
					$this->purge('events');
					break;
				default:
					$this->run_sync('events');
					break;
			}

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

			case 'events':
				// Get the array of registered services
				$services = apply_filters( 'ccbpress_ccb_services', array() );
				// Check for group related services
				if ( in_array( 'event_profiles', $services ) || in_array( 'event_profile', $services ) ) {
					return TRUE;
				}
				break;

		}

		return FALSE;

	}

	private function run_sync( $what, $sync_all = FALSE ) {

		$ccbpress_sync_options = get_option('ccbpress_settings_sync', array() );

		switch( $what ) {

			case 'groups':

				$include_image_link = '0';
				if ( isset( $ccbpress_sync_options['group_include_images'] ) ) {
					$include_image_link = '1';
				}

				if ( 'Never' === ( $last_sync = get_option( 'ccbpress_last_group_sync', 'Never' ) ) || $sync_all ) {
					$modified_since = (string) date('Y-m-d', strtotime( '-6 months', current_time('timestamp') ) );
				} else {
					$modified_since = (string) date('Y-m-d', strtotime( $last_sync ) );
				}

				CCBPress()->sync->push_to_queue( array(
					'srv' => 'group_profiles',
					'args' => array(
						'include_participants'	=> '0',
						'include_image_link'	=> $include_image_link,
						'page'					=> 1,
						'per_page'				=> 100,
						'modified_since'		=> $modified_since,
						'cache_lifespan'		=> 0,
					),
				) );
				CCBPress()->sync->save()->dispatch();
				break;

			case 'events':

				$include_image_link = '0';
				if ( isset( $ccbpress_sync_options['event_include_images'] ) ) {
					$include_image_link = '1';
				}

				if ( 'Never' === ( $last_sync = get_option( 'ccbpress_last_event_sync', 'Never' ) ) || $sync_all ) {
					$modified_since = null;
				} else {
					$modified_since = (string) date('Y-m-d', strtotime( $last_sync ) );
				}

				CCBPress()->sync->push_to_queue( array(
					'srv' => 'event_profiles',
					'args' => array(
						'include_guest_list'	=> '0',
						'include_image_link'	=> $include_image_link,
						'page'					=> 1,
						'per_page'				=> 100,
						'modified_since'		=> $modified_since,
						'cache_lifespan'		=> 0,
					),
				) );
				CCBPress()->sync->save()->dispatch();
				break;

		}

	}

	private function purge( $what ) {

		switch( $what ) {

			case 'groups':

				$group_profiles_db = new CCBPress_Group_Profiles_DB();
				$group_profiles_db->purge( strtotime('yesterday', current_time('timestamp') ) );
				unset( $group_profiles_db );

				break;

			case 'events':

				$event_profiles_db = new CCBPress_Event_Profiles_DB();
				$event_profiles_db->purge( strtotime('yesterday', current_time('timestamp') ) );
				unset( $event_profiles_db );

				break;

		}

	}

}

// add_action('ccbpress_daily_maintenance', 'ccbpress_daily_maintenance' );
function ccbpress_daily_maintenance() {
	new CCBPress_Maintenance();
}
