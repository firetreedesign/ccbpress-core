<?php
/**
 * Purge all cache
 */
function ccbpress_core_purge_all_cache() {

	if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'ccbpress-purge-all-cache' ) ) {

		global $wpdb;

		$cache = $wpdb->get_col( "SELECT option_name FROM $wpdb->options where option_name LIKE '_transient_timeout_ccbpress_%'" );

		if ( ! empty( $cache ) ) {

			foreach ( $cache as $transient ) {

				$name = str_replace( '_transient_timeout_', '', $transient );
				delete_transient( $name );

			}

		}

		unset( $cache );

	}

	wp_redirect( wp_get_referer() );
	die();

}
add_action( 'admin_post_ccbpress-purge-all-cache', 'ccbpress_core_purge_all_cache' );
