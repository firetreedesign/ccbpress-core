<?php
/**
 * Setup Church Community Builder Services
 *
 * @package CCBPress_Core
 * @since   1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Setup the CCB services that we will be using
 */
class CCBPress_Services {

    public function __construct() {
        $this->filters();
    }

    private function filters() {
        add_filter( 'ccbpress_ccb_services', array( $this, 'setup_services' ) );
    }

    /**
     * Here we define the services we are using
     */
    public function setup_services( $services ) {

		if ( ! in_array( 'individual_profile_from_id', $services, true ) ) {
			$services[] = 'individual_profile_from_id';
		}

		if ( ! in_array( 'form_list', $services, true ) ) {
			$services[] = 'form_list';
		}

        if ( ! in_array( 'group_profiles', $services, true ) ) {
			$services[] = 'group_profiles';
		}

		if ( ! in_array( 'group_profile_from_id', $services, true ) ) {
			$services[] = 'group_profile_from_id';
		}

        return $services;

    }

}
new CCBPress_Services();
