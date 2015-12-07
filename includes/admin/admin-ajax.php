<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class CCBPress_Admin_Ajax {

	public function __construct() {
		add_action( 'wp_ajax_ccbpress_check_services', array( $this, 'check_services' ) );
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


}
new CCBPress_Admin_Ajax();
