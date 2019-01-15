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

	// Delete the options.
	delete_option( 'ccbpress_settings' );
	delete_option( 'ccbpress_ccb' );
	delete_option( 'ccbpress_licenses' );
	delete_option( 'ccbpress_settings_import' );
	delete_option( 'ccbpress_last_import' );
	delete_option( 'ccbpress_import_in_progress' );
	delete_option( 'ccbpress_settings_sync' );
	delete_option( 'ccbpress_last_group_sync' );
	delete_option( 'ccbpress_group_sync_in_progress' );
	delete_option( 'ccbpress_last_event_sync' );
	delete_option( 'ccbpress_event_sync_in_progress' );
	delete_option( 'ccbpress_rate_limit' );
	delete_option( 'ccbpress_cancel_import' );
	delete_option( 'ccbpress_current_import' );

	// Delete any left-over import jobs.
	global $wpdb;	
	$table  = $wpdb->options;
	$column = 'option_name';
	$key = 'wp_ccbpress_get_batch_%';
	if ( is_multisite() ) {
		$table  = $wpdb->sitemeta;
		$column = 'meta_key';
	}
	$wpdb->query( $wpdb->prepare( "DELETE FROM $table WHERE $column LIKE %s", $key ) );

}
