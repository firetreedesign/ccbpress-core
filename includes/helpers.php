<?php
function ccbpress_get_license( $item ) {
	$licenses = get_option( 'ccbpress_licenses', array() );
	if ( isset( $licenses[ $item ] ) ) {
		return $licenses[ $item ];
	}
	return false;
}
