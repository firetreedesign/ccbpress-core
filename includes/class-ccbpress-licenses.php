<?php
/**
 * CCBPress License Handler
 *
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'CCBPress_License' ) ) :

	class CCBPress_License {

		private $file;
		private $license;
		private $item_name;
		private $item_id;
		private $item_shortname;
		private $version;
		private $author;
		private $api_url = 'https://ccbpress.com/';

	    /**
	     * Create a new instance
	     */
	    function __construct( $_file, $_item_id, $_item_name, $_version, $_author ) {

	        $this->file				= $_file;
			$this->item_name		= $_item_name;
			$this->item_id			= $_item_id;
			$this->item_shortname	= 'ccbpress_' . $this->item_id;
			$this->version			= $_version;
			$this->author			= $_author;
			$this->license			= trim( $this->get_license_key() );

			$this->includes();
			$this->hooks();

	    }

		/**
		 * Include the EDD Sofitware Licensing updater class
		 *
		 * @since 1.0.0
		 *
		 * @access private
		 *
		 * @return void
		 */
		private function includes() {
			if ( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
				require CCBPRESS_CORE_PLUGIN_DIR . 'lib/EDD_SL_Plugin_Updater.php';
			}
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

			// Register our license key setting.
			add_filter( 'ccbpress_license_keys', array( $this, 'licenses' ) );

			// Activate the license key when settings are saved.
			add_action( 'admin_init', array( $this, 'activate_license' ) );

			// Deactivate the license key.
			add_action( 'admin_init', array( $this, 'deactivate_license' ) );

			// Register the auto updater.
			add_action( 'admin_init', array( $this, 'auto_updater' ), 0 );

		}

		private function get_license_key() {
			$licenses = get_option( 'ccbpress_licenses', array() );
			if ( isset( $licenses[ $this->item_shortname . '_license_key' ] ) ) {
				return $licenses[ $this->item_shortname . '_license_key' ];
			}
			return false;
		}

		/**
		 * Auto updater
		 *
		 * @since 1.0.0
		 *
		 * @access private
		 *
		 * @return void
		 */
		public function auto_updater() {

			if ( 'valid' !== get_option( $this->item_shortname . '_license_key_active' ) ) {
			 	return;
			}

			$edd_updater = new EDD_SL_Plugin_Updater(
				$this->api_url,
				$this->file,
				array(
					'version'	=> $this->version,
					'license'	=> $this->license,
					'item_name'	=> $this->item_name,
					'author'	=> $this->author,
					'url'		=> home_url()
				)
			);

		}

		/**
		 * Activate the license key
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 *
		 * @return void
		 */
		public function activate_license() {

			if ( ! isset( $_POST['ccbpress_licenses'] ) ) {
				return;
			}

			if ( ! isset( $_POST['ccbpress_licenses'][ $this->item_shortname . '_license_key' ] ) ) {
				return;
			}

			foreach( $_POST as $key => $value ) {
				if ( false !== strpos( $key, 'license_key_deactivate' ) ) {
					return;
				}
			}

			if ( ! wp_verify_nonce( $_REQUEST[ $this->item_shortname . '_license_key-nonce'], $this->item_shortname . '_license_key-nonce' ) ) {
				wp_die( __('Nonce verification failed', 'ccbpress-core'), __('Error', 'ccbpress-core'), array( 'response' => 403 ) );
			}

			if ( 'valid' === get_option( $this->item_shortname . '_license_key_active' ) ) {
				return;
			}

			$license = sanitize_text_field( $_POST['ccbpress_licenses'][ $this->item_shortname . '_license_key' ] );

			if ( empty( $license ) ) {
				return;
			}

			$api_params = array(
				'edd_action'	=> 'activate_license',
				'license'		=> $license,
				'item_name'		=> urlencode( $this->item_name ),
				'url'			=> home_url()
			);

			$response = wp_remote_post(
				$this->api_url,
				array(
					'timeout'	=> 15,
					'sslverify'	=> false,
					'body'		=> $api_params
				)
			);

			// Check for errors
			if ( is_wp_error( $response ) ) {
				return;
			}

			// Make WordPress look for updates
			set_site_transient( 'update_plugins', null );

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			update_option( $this->item_shortname . '_license_key_active', $license_data->license );

		}

		/**
		 * Deactivate the license key
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 *
		 * @return void
		 */
		public function deactivate_license() {

			if ( ! isset( $_POST['ccbpress_licenses'] ) ) {
				return;
			}

			if ( ! isset( $_POST['ccbpress_licenses'][ $this->item_shortname . '_license_key' ] ) ) {
				return;
			}

			if ( ! wp_verify_nonce( $_REQUEST[ $this->item_shortname . '_license_key-nonce'], $this->item_shortname . '_license_key-nonce' ) ) {
				wp_die( __('Nonce verification failed', 'ccbpress-core'), __('Error', 'ccbpress-core'), array( 'response' => 403 ) );
			}

			if ( isset( $_POST[ $this->item_shortname . '_license_key_deactivate' ] ) ) {

				$api_params = array(
					'edd_action'	=> 'deactivate_license',
					'license'		=> $this->license,
					'item_name'		=> urlencode( $this->item_name ),
					'url'			=> home_url()
				);

				$response = wp_remote_post(
					$this->api_url,
					array(
						'timeout'	=> 15,
						'sslverify'	=> false,
						'body'		=> $api_params
					)
				);

				// Check for errors
				if ( is_wp_error( $response ) ) {
					return;
				}

				$license_data = json_decode( wp_remote_retrieve_body( $response ) );
				update_option( 'ccbpress_license_data', $license_data );
				delete_option( $this->item_shortname . '_license_key_active' );

			}

		}

		/**
		 * Add the license key field to the settings
		 *
		 * @since 1.0.0
		 *
		 * @param  array $settings
		 *
		 * @return array
		 */
		public function licenses( $licenses ) {

			$licenses[] = array(
				'id'	=> esc_attr( $this->item_shortname ),
				'name'	=> esc_html( $this->item_name ),
				'notes'	=> '',
			);

			return $licenses;

		}

	}

endif;
