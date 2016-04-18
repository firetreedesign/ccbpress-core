<?php
function ccbpress_schedule_get( $get_url, $cache_lifespan, $api_user, $api_pass, $transient_prefix ) {

	CCBPress()->ccb->get( $get_url, $cache_lifespan );

}
add_action( 'ccbpress_schedule_get', 'ccbpress_schedule_get', 10, 5 );
