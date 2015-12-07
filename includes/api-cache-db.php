<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class CCBPress_API_Cache_DB {

	public $table_name = 'ccbpress_api_cache';
	public $db_version = '1.0';

	public function __construct() {
		global $wpdb;
		$this->table_name = $wpdb->prefix . $this->table_name;

		register_activation_hook( CCBPRESS_CORE_PLUGIN_FILE, array( $this, 'create_table' ) );
	}

	public function create_table() {

		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $this->table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			url text NOT NULL,
			content longtext NOT NULL,
			expiration_date timestamp NOT NULL,
			UNIQUE KEY id (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		//add_option( 'ccbpress_api_cache_db_version', $this->db_version );

	}

	/**
	 * Add the cache to the database
	 *
	 * @param $data array
	 */
	public function insert( $data = array() ) {

		global $wpdb;

		$defaults = array(
			'url'				=> '',
			'content'			=> '',
			'expiration_date'	=> date('Y-m-d H:i:s', strtotime('+1 hour') )
		);

		$new_data = wp_parse_args( $data, $defaults );

		return $wpdb->insert( $this->table_name, $new_data );

	}

	public function delete( $url = false ) {

		if ( empty( $url ) ) {
			return false;
		}

		global $wpdb;

		return $wpdb->delete( $this->table_name, array( 'url' => $url ) );

	}

	public function get( $url = false ) {

		if ( empty( $url ) ) {
			return false;
		}

		global $wpdb;
		$results = $wpdb->get_results( "SELECT * FROM $this->table_name WHERE url = '$url' LIMIT 1", OBJECT );

		foreach( $results as $result ) {
			return $result->content;
		}
		return false;
	}

	public function get_cache( $url = false, $cache_lifespan ) {

		if ( empty( $url ) ) {
			return false;
		}

		global $wpdb;
		$results = $wpdb->get_results( "SELECT * FROM $this->table_name WHERE url = '$url' LIMIT 1", OBJECT );

		foreach( $results as $result ) {
			if ( date('Y-m-d H:i:s') > $result->expiration_date ) {
				return $result->content;
			} else { // The cache has expired
				// Delete it from the database
				$this->delete( $url );

				// Schedule the data to be repopulated
				wp_clear_scheduled_hook( 'ccbpress_api_cache_schedule_get', array( $url, $cache_lifespan ) );
				wp_schedule_single_event( time(), 'ccbpress_api_cache_schedule_get', array( $url, $cache_lifespan ) );

				// Return the data
				return $result->content;
			}
		}

		return false;

	}

}
