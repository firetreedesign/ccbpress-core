<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class CCBPress_Dashboard_Widgets {

	public function init() {
		add_action( 'plugins_loaded', array( $this, 'actions' ) );
	}

	/**
	 * Action hooks
	 *
	 * @return void
	 */
	public function actions() {

		if ( ! CCBPress()->ccb->is_connected() ) {
			return;
		}

		add_action( 'wp_dashboard_setup', array( $this, 'api_status_setup' ) );

	}

	public function api_status_setup() {
		wp_add_dashboard_widget(
			'ccbpress_activity_dashboard_widget',
			__( 'Church Community Builder API Status', 'ccbpress-core' ),
			array( $this, 'api_status_display' ),
			null,
			null
		);
	}

	public function api_status_display() {
		$api_status = CCBPress()->ccb->get( array(
			'cache_lifespan'	=> 0,
			'query_string'		=> array(
				'srv'	=> 'api_status',
			),
		) );

		if ( ! is_object( $api_status ) ) {
			return;
		}
		?>
		<table class="ccbpress-dash-widget-api-status">
			<tr>
				<td>
					<div><?php echo (string) number_format( floatval( $api_status->response->counter ) ); ?></div>
					<?php esc_html_e( 'requests today', 'ccbpress-core' ); ?>
				</td>
				<td>
					<div><?php echo (string) number_format( floatval( $api_status->response->daily_limit ) ); ?></div>
					<?php esc_html_e( 'your daily limit', 'ccbpress-core' ); ?>
				</td>
			</tr>
		</table>
		<?php
	}

}
$ccbpress_dashboard_widgets = new CCBPress_Dashboard_Widgets();
$ccbpress_dashboard_widgets->init();
