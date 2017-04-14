<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class CCBPress_Admin_Ajax {

	public function __construct() {
		add_action( 'wp_ajax_ccbpress_check_services', array( $this, 'check_services' ) );
	}

	public function check_services() {

		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'ccbpress-nonce' ) ) {
			die( esc_html( 'Insufficient Permissions', 'ccbpress-core' ) );
		}

		$services = apply_filters( 'ccbpress_ccb_services', array() );

		if ( ! in_array( 'group_profiles', $services, true ) ) {
			$services[] = 'group_profiles';
		}

		if ( ! in_array( 'group_profile_from_id', $services, true ) ) {
			$services[] = 'group_profile_from_id';
		}

		sort( $services );

		if ( 0 === count( $services ) ) {
			echo '<br /><div class="notice"><p><span class="dashicons dashicons-info"></span> ' . esc_html( 'There are no services registered with CCBPress.', 'ccbpress-core' ) . '</p></div>';
			wp_die();
		}

		echo '<br />';
		echo '<table class="wp-list-table widefat fixed striped">';
		echo '	<thead>';
		echo '		<tr>';
		echo '			<th style="padding: 8px 10px;">' . esc_html__( 'API Service', 'ccbpress-core' ) . '</th>';
		echo '			<th style="padding: 8px 10px;">' . esc_html__( 'Status', 'ccbpress-core' ) . '</th>';
		echo '		</tr>';
		echo '	</thead>';
		echo '	<tbody>';

		foreach ( $services as $service ) {

			$url = add_query_arg( 'srv', $service, CCBPress()->ccb->api_url );
			$url = add_query_arg( 'describe_api', '1', $url );
			$ccb_data = CCBPress()->ccb->get( $url, 0 );

			$service_result = array();
			$service_result[] = CCBPress()->ccb->is_valid_describe( $ccb_data );
			$service_result[] = $ccb_data;

			if ( true === $service_result[0] ) {
				echo '		<tr>';
				echo '			<td>' . esc_html( $service ) . '</td>';
				echo '			<td><div class="dashicons dashicons-yes"></div>' . esc_html__( 'Passed', 'ccbpress-core' ) . '</td>';
				echo '		</tr>';
			} else {
				echo '		<tr>';
				echo '			<td>' . esc_html( $service ) . '</td>';
				echo '			<td><div class="dashicons dashicons-no"></div> ' . esc_html( $service_result[1]->response->errors->error['type'] ) . ' - ' . esc_html( $service_result[1]->response->errors->error ) . '</td>';
				echo '		</tr>';
			}

			// Free up the memory.
			unset( $service_result );

		}

		echo '	</tbody>';
		echo '</table>';

		wp_die();

	}

}
new CCBPress_Admin_Ajax();
