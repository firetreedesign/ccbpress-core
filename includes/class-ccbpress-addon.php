<?php
/**
 * CCBPress Addon Handler
 *
 * @since 1.0.0
 * @package CCBPress Core
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'CCBPress_Addon' ) ) :

	/**
	 * CCBPress Addon class
	 */
	class CCBPress_Addon {

		/**
		 * Services
		 *
		 * @var array
		 */
		private $services;

		/**
		 * Support Topics
		 *
		 * @var array
		 */
		private $support_topics;

		/**
		 * Import jobs
		 *
		 * @var array
		 */
		private $import_jobs;

		/**
		 * Uninstall Variables
		 *
		 * @var array
		 */
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

				if ( isset( $_args['import_jobs'] ) ) {
					$this->import_jobs = $_args['import_jobs'];
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
			add_filter( 'ccbpress_import_jobs', array( $this, 'import_jobs' ) );
			add_filter( 'ccbpress_uninstall_settings', array( $this, 'uninstall_settings' ) );

			add_filter('ccbpress_enable_beacon', function() {} );

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
		 * Add the import options
		 *
		 * @since 1.0.0
		 *
		 * @param  array $import The import options.
		 *
		 * @return array         The new import options
		 */
		public function import_jobs( $jobs ) {

			if ( ! is_array( $this->import_jobs ) ) {
				return $jobs;
			}

			foreach ( $this->import_jobs as $job ) {

				if ( ! isset( $job['service'] ) ) {
					continue;
				}

				if ( in_array( $job['service'], $jobs, true ) ) {
					continue;
				}

				$jobs[ $job['service'] ] = $job;

			}

			return $jobs;

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
