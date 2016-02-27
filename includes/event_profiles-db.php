<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class CCBPress_Event_Profiles_DB {

	public $table_name = 'ccbpress_event_profiles';
	public $db_version = '1.0';

	public function __construct() {
		global $wpdb;
		$this->table_name = $wpdb->prefix . $this->table_name;
	}

	/**
	 * Creates the lists table
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function create_table() {

		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $this->table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			event_id mediumint(9) NOT NULL,
			name varchar(255),
			description longtext,
			leader_notes longtext,
			start_datetime timestamp,
			start_date varchar(255),
			start_time varchar(255),
			end_datetime timestamp,
			end_date varchar(255),
			end_time varchar(255),
			timezone varchar(255),
			recurrence_description varchar(255),
			approval_status_id mediumint(9),
			approval_status varchar(255),
			exceptions longtext,
			group_id mediumint(9),
			group_name varchar(255),
			organizer_id mediumint(9),
			organizer varchar(255),
			phone_type varchar(255),
			phone varchar(255),
			location_name varchar(255),
			location_street_address varchar(255),
			location_city varchar(255),
			location_state varchar(255),
			location_zip varchar(255),
			location_line_1 varchar(255),
			location_line_2 varchar(255),
			registration_limit mediumint(9),
			registration_event_type_id mediumint(9),
			registration_event_type varchar(255),
			registration_forms longtext,
			resources longtext,
			setup_start timestamp,
			setup_end timestamp,
			setup_notes longtext,
			event_grouping_id mediumint(9),
			event_grouping varchar(255),
			creator_id mediumint(9),
			creator varchar(255),
			modifier_id mediumint(9),
			modifier varchar(255),
			listed boolean NOT NULL DEFAULT 0,
			public_calendar_listed boolean NOT NULL DEFAULT 0,
			created timestamp NOT NULL,
			modified timestamp NOT NULL,
			last_sync timestamp NOT NULL,
			UNIQUE KEY id (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		add_option( 'ccbpress_event_profiles_db_version', $this->db_version );
		return $wpdb->last_error;
	}

	/**
	 * Drops the list table
	 *
	 * @since 1.0.0
	 *
	 * @return boolean The success/fail message
	 */
	public function drop_table() {

		global $wpdb;
		return $wpdb->query("DROP TABLE IF EXISTS $this->table_name");

	}

	/**
	 * Add an event to the database
	 *
	 * @since 1.0.0
	 *
	 * @param  array  $data The data to use for each field
	 *
	 * @return integer       The number of rows that were affected
	 */
	public function insert( $data = array() ) {

		$defaults = array(
			'last_sync' => date('Y-m-d H:i:s', current_time( 'timestamp' ) ),
		);

		$group = wp_parse_args( $data, $defaults );

		global $wpdb;
		return $wpdb->insert( $this->table_name, $group );

	}

	/**
	 * Update an event in the database
	 *
	 * @since 1.0.o
	 *
	 * @param  varies $event_id The event ID. Either a string or FALSE
	 * @param  array  $data    The fields and their values that are to be updated
	 *
	 * @return integer          The number of rows that were affected
	 */
	public function update( $event_id = false, $data = array() ) {

		if ( false === $event_id ) {
			return false;
		}

		global $wpdb;
		return $wpdb->update( $this->table_name, $data, array( 'event_id' => $event_id ) );

	}

	/**
	 * Delete an event from the database
	 *
	 * @since 1.0.0
	 *
	 * @param  varies $event_id The event ID. Either a string or FALSE
	 *
	 * @return integer          The number of rows that were affected
	 */
	public function delete( $event_id = FALSE ) {

		if ( FALSE === $event_id ) {
			return FALSE;
		}

		global $wpdb;
		return $wpdb->delete( $this->table_name, array( 'event_id' => $event_id ) );

	}

	public function purge( $last_sync = FALSE ) {

		if ( FALSE === $last_sync ) {
			return FALSE;
		}

		global $wpdb;
		return $wpdb->query( 'DELETE * FROM ' . $this->table_name . ' WHERE last_sync < ' . $last_sync );

	}

	/**
	 * Retrieves an event from the database
	 *
	 * @since 1.0.0
	 *
	 * @param  varies $event_id The event ID. Either a string or FALSE
	 *
	 * @return object          The query results object
	 */
	public function get( $event_id = false ) {

		if ( false === $event_id ) {
			return false;
		}

		global $wpdb;
		return $wpdb->get_results( "SELECT * FROM $this->table_name WHERE event_id = '$event_id'", OBJECT );

	}

	/**
	 * Retrieves all of the events from the database
	 *
	 * @since 1.0.0
	 *
	 * @return array The query results array
	 */
	public function get_all() {

		global $wpdb;
		return $wpdb->get_results( "SELECT * FROM $this->table_name ORDER BY name ASC", OBJECT );

	}

}
