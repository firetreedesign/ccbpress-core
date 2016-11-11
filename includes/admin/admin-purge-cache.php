<?php
/**
 * CCBPress Purge Cache
 *
 * @package CCBPress
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CCBPress Purge Cache
 */
class CCBPress_Purge_Cache {

	/**
	 * Init
	 *
	 * @since 1.0.2
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'admin_post_ccbpress-purge-all-cache', 'CCBPress_Purge_Cache::purge_all_cache_post' );
		add_action( 'admin_post_ccbpress-purge-image-cache', 'CCBPress_Purge_Cache::purge_images_post' );
		add_action( 'admin_post_ccbpress-purge-db-cache', 'CCBPress_Purge_Cache::purge_db_post' );
		add_action( 'admin_post_ccbpress-purge-transient-cache', 'CCBPress_Purge_Cache::purge_transients_post' );
	}

	/**
	 * Purge all cache
	 */
	public static function purge_all_cache_post() {

		if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'ccbpress-purge-all-cache' ) ) {
			CCBPress_Purge_Cache::purge_transients();
			CCBPress_Purge_Cache::purge_image_cache();
			CCBPress_Purge_Cache::purge_db();
		}

		wp_redirect( wp_get_referer() );
		die();

	}

	/**
	 * Purge image cache
	 */
	public static function purge_images_post() {

		if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'ccbpress-purge-image-cache' ) ) {
			CCBPress_Purge_Cache::purge_images();
		}

		wp_redirect( wp_get_referer() );
		die();

	}

	/**
	 * Purge database cache
	 */
	public static function purge_db_post() {

		if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'ccbpress-purge-db-cache' ) ) {
			CCBPress_Purge_Cache::purge_db();
		}

		wp_redirect( wp_get_referer() );
		die();

	}

	/**
	 * Purge Transient cache
	 */
	public static function purge_transients_post() {

		if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'ccbpress-purge-transient-cache' ) ) {
			CCBPress_Purge_Cache::purge_transients();
		}

		wp_redirect( wp_get_referer() );
		die();

	}

	/**
	 * Purge Transient Cache
	 *
	 * @since 1.0.2
	 *
	 * @return void
	 */
	public static function purge_transients() {

		global $wpdb;

		$cache = $wpdb->get_col( "SELECT option_name FROM $wpdb->options where option_name LIKE '_transient_timeout_ccbp_%'" );

		if ( ! empty( $cache ) ) {
			foreach ( $cache as $transient ) {
				$name = str_replace( '_transient_timeout_', '', $transient );
				delete_transient( $name );
			}
		}

		unset( $cache );

	}

	/**
	 * Purge image cache
	 */
	public static function purge_images() {
		ccbpress()->ccb->purge_image_cache();
	}

	/**
	 * Purge the database cache
	 *
	 * @since 1.0.2
	 *
	 * @return void
	 */
	public static function purge_db() {

		// Purge the event_profiles table.
		$event_profiles_db = new CCBPress_Event_Profiles_DB();
		$event_profiles_db->purge_all();
		unset( $event_profiles_db );

		// Drop the group_profiles table.
		$group_profiles_db = new CCBPress_Group_Profiles_DB();
		$group_profiles_db->purge_all();
		unset( $group_profiles_db );

		delete_option( 'ccbpress_last_event_sync' );
		delete_option( 'ccbpress_last_group_sync' );

	}

}
CCBPress_Purge_Cache::init();
