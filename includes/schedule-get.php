<?php
function ccbpress_schedule_get( $args ) {
	CCBPress()->ccb->get_new( $args );
}
add_action( 'ccbpress_schedule_get', 'ccbpress_schedule_get', 10, 5 );
