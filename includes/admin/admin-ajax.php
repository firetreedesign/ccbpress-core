<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class CCBPress_Admin_Ajax {

	public function __construct() {
		add_action( 'wp_ajax_ccbpress_check_services', array( $this, 'check_services' ) );
		add_action( 'wp_ajax_ccbpress_sync_groups', array( $this, 'sync_groups' ) );
		add_action( 'wp_ajax_ccbpress_sync_groups_status', array( $this, 'sync_groups_status' ) );
		add_action( 'wp_ajax_ccbpress_last_group_sync', array( $this, 'last_group_sync' ) );
		add_action( 'wp_ajax_ccbpress_sync_events', array( $this, 'sync_events' ) );
		add_action( 'wp_ajax_ccbpress_sync_events_status', array( $this, 'sync_events_status' ) );
		add_action( 'wp_ajax_ccbpress_last_event_sync', array( $this, 'last_event_sync' ) );
	}

	public function check_services() {

		if ( ! isset( $_POST[ 'ccbpress_nonce' ] ) || ! wp_verify_nonce( $_POST[ 'ccbpress_nonce' ], 'ccbpress-nonce' ) ) {
			die( 'Insufficient Permissions' );
		}

		$services = apply_filters( 'ccbpress_ccb_services', array() );
		sort( $services );

		if ( count( $services ) == 0 ) {
			echo '<br /><div class="notice"><p><span class="dashicons dashicons-info"></span> ' . __('There are no services registered with CCBPress.') . '</p></div>';
			wp_die();
		}

		echo '<br />';
		echo '<table class="wp-list-table widefat fixed striped">';
		echo '	<thead>';
		echo '		<tr>';
		echo '			<th style="padding: 8px 10px;">' . __( 'API Service', 'ccbpress' ) . '</th>';
		echo '			<th style="padding: 8px 10px;">' . __( 'Status', 'ccbpress' ) . '</th>';
		echo '		</tr>';
		echo '	</thead>';
		echo '	<tbody>';

		foreach( $services as $service ) {

			$url = add_query_arg( 'srv', $service, CCBPress()->ccb->api_url );
			$url = add_query_arg( 'describe_api', '1', $url );
			$ccb_data = CCBPress()->ccb->get( $url, 0 );

			$service_result = array();
			$service_result[] = CCBPress()->ccb->is_valid_describe( $ccb_data );
			$service_result[] = $ccb_data;

			if ( $service_result[0] === true ) {
				echo '		<tr>';
				echo '			<td>' . $service . '</td>';
				echo '			<td><div class="dashicons dashicons-yes"></div> Passed</td>';
				echo '		</tr>';
			} else {
				echo '		<tr>';
				echo '			<td>' . $service . '</td>';
				echo '			<td><div class="dashicons dashicons-no"></div> ' . $service_result[1]->response->errors->error['type'] . ' - ' . $service_result[1]->response->errors->error . '</td>';
				echo '		</tr>';
			}

			// Free up the memory
			unset( $service_result );

		}

		echo '	</tbody>';
		echo '</table>';

		wp_die();

	}

	public function sync_groups() {

		if ( ! isset( $_POST[ 'ccbpress_nonce' ] ) || ! wp_verify_nonce( $_POST[ 'ccbpress_nonce' ], 'ccbpress-nonce' ) ) {
			die( 'Insufficient Permissions' );
		}

		$ccbpress_sync_options = get_option('ccbpress_settings_sync', array() );

		$include_image_link = '0';
		if ( isset( $ccbpress_sync_options['group_include_images'] ) ) {
			$include_image_link = '1';
		}

		if ( 'Never' === ( $last_sync = get_option( 'ccbpress_last_group_sync', 'Never' ) ) ) {
			$modified_since = (string) date('Y-m-d', strtotime( '-6 months', current_time('timestamp') ) );
		} else {
			$modified_since = (string) date('Y-m-d', strtotime( $last_sync ) );
		}

		CCBPress()->sync->push_to_queue( array(
			'srv' => 'group_profiles',
			'args' => array(
				'include_participants'	=> '0',
				'include_image_link'	=> $include_image_link,
				'page'					=> 1,
				'per_page'				=> 100,
				'modified_since'		=> $modified_since,
				'cache_lifespan'		=> 0,
			),
		) );
		CCBPress()->sync->save()->dispatch();

		echo 'started';

		wp_die();

	}

	public function sync_groups_status() {

		if ( ! isset( $_POST[ 'ccbpress_nonce' ] ) || ! wp_verify_nonce( $_POST[ 'ccbpress_nonce' ], 'ccbpress-nonce' ) ) {
			die( 'Insufficient Permissions' );
		}

		// Send back the number of complete payments
		if ( $status = get_option( 'ccbpress_group_sync_in_progress', false ) ) {
			echo '<strong>' . $status . '...</strong><br /><i>Sync is running in the background. Leaving this page will not interrupt the process.</i>';
		} else {
			echo 'false';
		}

		wp_die();

	}

	public function last_group_sync() {

		if ( ! isset( $_POST[ 'ccbpress_nonce' ] ) || ! wp_verify_nonce( $_POST[ 'ccbpress_nonce' ], 'ccbpress-nonce' ) ) {
			die( 'Insufficient Permissions' );
		}

		$last_sync = get_option( 'ccbpress_last_group_sync', 'Never' );
		if ( 'Never' === $last_sync ) {
			echo $last_sync;
		} else {
			echo human_time_diff( strtotime('now', current_time('timestamp') ), strtotime( $last_sync, current_time('timestamp') ) ) . ' ago';
		}

		wp_die();

	}

	public function sync_events() {

		if ( ! isset( $_POST[ 'ccbpress_nonce' ] ) || ! wp_verify_nonce( $_POST[ 'ccbpress_nonce' ], 'ccbpress-nonce' ) ) {
			die( 'Insufficient Permissions' );
		}

		$ccbpress_sync_options = get_option('ccbpress_settings_sync', array() );

		if ( 'Never' === ( $last_sync = get_option( 'ccbpress_last_event_sync', 'Never' ) ) ) {
			$modified_since = NULL;
		} else {
			$modified_since = (string) date('Y-m-d', strtotime( $last_sync ) );
		}

		CCBPress()->sync->push_to_queue( array(
			'srv' => 'event_profiles',
			'args' => array(
				'include_guest_list'	=> '0',
				'page'					=> 1,
				'per_page'				=> 100,
				'modified_since'		=> $modified_since,
				'cache_lifespan'		=> 0,
			),
		) );
		CCBPress()->sync->save()->dispatch();

		echo 'started';

		wp_die();

	}

	public function sync_events_status() {

		if ( ! isset( $_POST[ 'ccbpress_nonce' ] ) || ! wp_verify_nonce( $_POST[ 'ccbpress_nonce' ], 'ccbpress-nonce' ) ) {
			die( 'Insufficient Permissions' );
		}

		// Send back the number of complete payments
		if ( $status = get_option( 'ccbpress_event_sync_in_progress', false ) ) {
			echo '<strong>' . $status . '...</strong><br /><i>Sync is running in the background. Leaving this page will not interrupt the process.</i>';
		} else {
			echo 'false';
		}

		wp_die();

	}

	public function last_event_sync() {

		if ( ! isset( $_POST[ 'ccbpress_nonce' ] ) || ! wp_verify_nonce( $_POST[ 'ccbpress_nonce' ], 'ccbpress-nonce' ) ) {
			die( 'Insufficient Permissions' );
		}

		$last_sync = get_option( 'ccbpress_last_event_sync', 'Never' );
		if ( 'Never' === $last_sync ) {
			echo $last_sync;
		} else {
			echo human_time_diff( strtotime('now', current_time('timestamp') ), strtotime( $last_sync, current_time('timestamp') ) ) . ' ago';
		}

		wp_die();

	}

}
new CCBPress_Admin_Ajax();
