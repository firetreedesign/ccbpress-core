<?php
/**
 * CCBPress Core Admin Ajax
 *
 * @since 1.0.0
 *
 * @package CCBPress Core
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CCBPress Admin Ajax
 */
class CCBPress_Admin_Ajax {

	/**
	 * Initialize the class
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'wp_ajax_ccbpress_check_services', array( $this, 'check_services' ) );
	}

	/**
	 * Check the services
	 *
	 * @return void
	 */
	public function check_services() {

		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'ccbpress-nonce' ) ) {
			die( esc_html__( 'Insufficient Permissions', 'ccbpress-core' ) );
		}

		$services = apply_filters( 'ccbpress_ccb_services', array() );

		sort( $services );

		if ( 0 === count( $services ) ) {
			echo '<br /><div class="notice"><p><span class="dashicons dashicons-info"></span> ' . esc_html__( 'There are no services registered with CCBPress.', 'ccbpress-core' ) . '</p></div>';
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

			$ccb_data = CCBPress()->ccb->get( array(
				'cache_lifespan'	=> 0,
				'refresh_cache'		=> 1,
				'query_string'		=> array(
					'srv'			=> $service,
					'describe_api'	=> '1',
				),
			) );

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
$ccbpress_admin_ajax = new CCBPress_Admin_Ajax();
$ccbpress_admin_ajax->init();
