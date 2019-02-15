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
		CCBPress()->ccb->purge_image_cache();
		delete_option( 'ccbpress_last_import' );
	}


}
