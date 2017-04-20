<?php
function ccbpress_schedule_get( $args ) {
	CCBPress()->ccb->get( $args );
}
add_action( 'ccbpress_schedule_get', 'ccbpress_schedule_get', 10 );
