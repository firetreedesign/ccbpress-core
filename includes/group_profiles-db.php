<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class CCBPress_Group_Profiles_DB {

	public $table_name = 'ccbpress_group_profiles';
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
			group_id mediumint(9) NOT NULL,
			name varchar(255),
			description longtext,
			campus_id mediumint(9),
			main_leader_id mediumint(9),
			main_leader_first_name varchar(255),
			main_leader_last_name varchar(255),
			main_leader_full_name varchar(255),
			main_leader_email varchar(255),
			main_leader_phones longtext,
			group_type_id mediumint(9),
			department_id mediumint(9),
			area_id mediumint(9),
			calendar_feed varchar(255),
			registration_forms longtext,
			current_members varchar(255),
			group_capacity varchar(255),
			addresses longtext,
			meeting_day_id mediumint(9),
			meeting_day varchar(255),
			meeting_time_id mediumint(9),
			meeting_time varchar(255),
			childcare_provided boolean NOT NULL DEFAULT 0,
			interaction_type varchar(255),
			membership_type varchar(255),
			notification boolean NOT NULL DEFAULT 0,
			user_defined_fields longtext,
			listed boolean NOT NULL DEFAULT 0,
			public_search_listed boolean NOT NULL DEFAULT 0,
			inactive boolean NOT NULL DEFAULT 0,
			creator_id mediumint(9),
			creator varchar(255),
			modifier_id mediumint(9),
			modifier varchar(255),
			created timestamp NOT NULL,
			modified timestamp NOT NULL,
			last_sync timestamp NOT NULL,
			UNIQUE KEY id (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		add_option( 'ccbpress_group_profiles_db_version', $this->db_version );

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
	 * Add a group to the database
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
	 * Update a group in the database
	 *
	 * @since 1.0.o
	 *
	 * @param  varies $group_id The group ID. Either a string or FALSE
	 * @param  array  $data    The fields and their values that are to be updated
	 *
	 * @return integer          The number of rows that were affected
	 */
	public function update( $group_id = false, $data = array() ) {

		if ( false === $group_id ) {
			return false;
		}

		global $wpdb;
		return $wpdb->update( $this->table_name, $data, array( 'group_id' => $group_id ) );

	}

	/**
	 * Delete a group from the database
	 *
	 * @since 1.0.0
	 *
	 * @param  varies $group_id The group ID. Either a string or FALSE
	 *
	 * @return integer          The number of rows that were affected
	 */
	public function delete( $group_id = false ) {

		if ( false === $group_id ) {
			return false;
		}

		global $wpdb;
		return $wpdb->delete( $this->table_name, array( 'group_id' => $group_id ) );

	}

	/**
	 * Retrieves a group from the database
	 *
	 * @since 1.0.0
	 *
	 * @param  varies $group_id The group ID. Either a string or FALSE
	 *
	 * @return object          The query results object
	 */
	public function get( $group_id = false ) {

		if ( false === $group_id ) {
			return false;
		}

		global $wpdb;
		return $wpdb->get_results( "SELECT * FROM $this->table_name WHERE group_id = '$group_id'", OBJECT );

	}

	/**
	 * Retrieves all of the groups from the database
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
