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

		// Find transients that contain expiration dates.
		$cache = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT option_name FROM $wpdb->options WHERE option_name LIKE %s",
				$wpdb->esc_like( '_transient_ccbp_' ) . '%'
			)
		);

		// Delete the transients.
		if ( ! empty( $cache ) ) {
			foreach ( $cache as $transient ) {
				$name = str_replace( '_transient_', '', $transient );
				if ( is_multisite() ) {
					delete_site_transient( $name );
				} else {
					delete_transient( $name );
				}
			}
		}

		unset( $cache );

		// Find and delete transients without expiration dates.
		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM $wpdb->options WHERE option_name LIKE %s AND autoload = %s",
				$wpdb->esc_like( '_transient_ccbp_' ) . '%',
				'yes'
			)
		);

	}

	/**
	 * Purge image cache
	 */
	public static function purge_images() {
		CCBPress()->ccb->purge_image_cache();
		delete_option( 'ccbpress_last_import' );
	}


}
