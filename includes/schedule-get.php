<?php
function ccbpress_schedule_get( $get_url, $cache_lifespan, $api_user, $api_pass, $transient_prefix ) {

	$transient_name = $transient_prefix . md5( $get_url );

	$args = array(
		'headers'	=> array(
			'Authorization' => 'Basic ' . base64_encode( $api_user . ':' . $api_pass )
			),
		'timeout'	=> 300,
		);

	$response = wp_remote_get( $get_url, $args );
	if ( ! is_wp_error( $response ) ) {

		// Grab the body from the response
		$ccb_data = wp_remote_retrieve_body( $response );

		// Save the transient data according to the $cache_lifespan
		if ( is_multisite() ) {
			set_site_transient( $transient_name, $ccb_data, $cache_lifespan * MINUTE_IN_SECONDS );
			set_site_transient( $transient_name . '_', $ccb_data, 10080 * MINUTE_IN_SECONDS );
		} else {
			set_transient( $transient_name, $ccb_data, $cache_lifespan * MINUTE_IN_SECONDS );
			set_transient( $transient_name . '_', $ccb_data, 10080 * MINUTE_IN_SECONDS );
		}

		CCBPress_Core()->ccb->find_images( $ccb_data );

	}

}
add_action( 'ccbpress_schedule_get', 'ccbpress_schedule_get', 10, 5 );
