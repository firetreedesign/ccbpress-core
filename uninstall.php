<?php
/**
 * Uninstall CCBPress Core
 *
 * @package		CCBPress Core
 * @subpackage	Uninstall
 * @copyright	Copyright (c) 2015, FireTree Design, LLC
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since		1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$ccbpress_settings = get_option( 'ccbpress_settings', array() );

if ( isset( $ccbpress_settings['remove_data'] ) ) {

	require_once plugin_dir_path( __FILE__ ) . 'includes/event_profiles-db.php';
	require_once plugin_dir_path( __FILE__ ) . 'includes/group_profiles-db.php';

	// Drop the event_profiles table.
	$event_profiles_db = new CCBPress_Event_Profiles_DB();
	$event_profiles_db->drop_table();
	unset( $event_profiles_db );

	// Drop the group_profiles table.
	$group_profiles_db = new CCBPress_Group_Profiles_DB();
	$group_profiles_db->drop_table();
	unset( $group_profiles_db );

	// Delete the options.
	delete_option( 'ccbpress_settings' );
	delete_option( 'ccbpress_ccb' );
	delete_option( 'ccbpress_licenses' );
	delete_option( 'ccbpress_settings_sync' );
	delete_option( 'ccbpress_last_group_sync' );
	delete_option( 'ccbpress_group_sync_in_progress' );
	delete_option( 'ccbpress_last_event_sync' );
	delete_option( 'ccbpress_event_sync_in_progress' );

}
