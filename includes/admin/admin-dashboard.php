<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class CCBPress_Dashboard_Widgets {

	public function __construct() {
		$ccbpress_ccb = get_option( 'ccbpress_ccb' );
		if ( isset( $ccbpress_ccb['connection_test'] ) && $ccbpress_ccb['connection_test'] === 'success' ) {
			add_action( 'wp_dashboard_setup', array( $this, 'api_status_setup' ) );
		}
	}

	public function api_status_setup() {
		wp_add_dashboard_widget(
			'ccbpress_activity_dashboard_widget',
			__('Church Community Builder API Status', 'ccbpress-core'),
			array( $this, 'api_status_display' ),
			null,
			null
		);
	}

	public function api_status_display() {
		$api_status = CCBPress()->ccb->api_status( array( 'cache_lifespan' => 0 ) );
		if ( ! is_object( $api_status ) ) return;
		?>
		<table class="ccbpress-dash-widget-api-status">
			<tr>
				<td>
					<div><?php echo (string) number_format( floatval( $api_status->response->counter ) ); ?></div>
					<?php _e('requests today', 'ccbpress-core'); ?>
				</td>
				<td>
					<div><?php echo (string) number_format( floatval( $api_status->response->daily_limit ) ); ?></div>
					<?php _e('your daily limit', 'ccbpress-core'); ?>
				</td>
			</tr>
		</table>
		<?php
	}

}
new CCBPress_Dashboard_Widgets();
