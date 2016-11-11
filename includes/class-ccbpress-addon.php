<?php
/**
 * CCBPress Addon Handler
 *
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'CCBPress_Addon' ) ) :

class CCBPress_Addon {

	private $services;
	private $support_topics;
	private $uninstall;

    /**
     * Create a new instance
     */
	 /**
	  * [__construct description]
	  *
	  * @since
	  *
	  * @param array $_addon    		item_name, item_id
	  * @param array $_license  		file, version, author
	  * @param array $_pages    		settings, tools
	  * @param array $_services 		Array of CCB services that the addon uses.
	  * @param array $_support_topics	Array of support topics for HS Beacon.
	  */
    function __construct( $_args ) {

		if ( ! isset( $_args['services'] ) ) {
			return;
		}

		$this->services = $_args['services'];

		if ( isset( $_args['support_topics'] ) ) {
			$this->support_topics = $_args['support_topics'];
		}

		if ( isset( $_args['uninstall'] ) ) {
			$this->uninstall = $_args['uninstall'];
		}

		$this->hooks();

    }

	/**
	 * Setup our hooks
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 *
	 * @return void
	 */
	private function hooks() {

		add_filter( 'ccbpress_ccb_services', array( $this, 'setup_services' ) );
		add_filter( 'ccbpress_support_topics', array( $this, 'support_topics' ) );
		add_filter( 'ccbpress_uninstall_settings', array( $this, 'uninstall_settings' ) );

	}

	/**
	 * Add the CCB services
	 *
	 * @since 1.0.0
	 *
	 * @param  array $services
	 *
	 * @return array
	 */
	public function setup_services( $services ) {

		if ( is_array( $this->services ) ) {

			foreach( $this->services as $service ) {
				if ( ! in_array( $service, $services ) ) {
					$services[] = $service;
				}
			}

		}

		return $services;

	}

	/**
	 * Add the HS Beacon support topics
	 *
	 * @since 1.0.0
	 *
	 * @param  array $topics The topics
	 *
	 * @return array         The new topics
	 */
	public function support_topics( $topics ) {

		add_filter('ccbpress_enable_beacon', function() {} );

		if ( is_array( $this->support_topics ) ) {
			foreach( $this->support_topics as $topic ) {
				$topics[] = array(
					'val'	=> $topic['val'],
					'label'	=> $topic['label'],
				);
			}
		}

		return $topics;

	}

	/**
	 * Add the Uninstall Settings
	 *
	 * @since 1.0.3
	 *
	 * @param  array $settings
	 *
	 * @return array
	 */
	public function uninstall_settings( $settings ) {

		if ( is_array( $this->uninstall ) && isset( $this->uninstall['id'] ) && isset( $this->uninstall['name'] ) ) {

			$settings[] = array(
				'id'	=> $this->uninstall['id'],
				'name'	=> $this->uninstall['name'],
			);

		}

		return $settings;

	}

}

endif;
