<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CCBPress Upgrades
 */
class CCBPress_Upgrades {

	/**
	 * Initialize the class
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'plugins_loaded', __CLASS__ . '::version_check' );
	}

	public static function version_check() {
		
		// Get the version from the options
		$version = get_option( 'ccbpress_core_db_version' );

		// If it's empty, assign it a version number
		if ( ! $version ) {
			$version = '1.0.0';
		}

		// Check if we've already run this
		if ( version_compare( $version, CCBPRESS_CORE_DB_VERSION, '=' ) ) {
			return;
		}

		// Version is before 1.0.1
		if ( version_compare( $version, '1.0.1', '<' ) ) {
			self::v1_1_11_upgrade();
		}

		// Version is before 1.3.2
		if ( version_compare( $version, '1.3.2', '<' ) ) {
			self::v1_3_2_upgrade();
		}

		update_option( 'ccbpress_core_db_version', CCBPRESS_CORE_DB_VERSION );

	}

	private static function v1_1_11_upgrade() {
		call_user_func( array( 'CCBPress_Core', 'unschedule_cron' ) );
		call_user_func( array( 'CCBPress_Core', 'schedule_cron' ) );
	}

	private static function v1_3_2_upgrade() {
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

}
CCBPress_Upgrades::init();
