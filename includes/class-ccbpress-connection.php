<?php
/**
 * Church Community Builder Connection
 *
 * @package     CCBPress_Core
 * @copyright   Copyright (c) 2015, FireTree Design, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CCBPress_Connection class
 *
 * @since 1.0.0
 */
class CCBPress_Connection {

	/**
	 * API Protocol
	 *
	 * @var $api_protocol
	 */
	private $api_protocol = 'https://';

	/**
	 * API Endpoint
	 *
	 * @var $api_endpoint
	 */
	private $api_endpoint = '.ccbchurch.com/api.php';

	/**
	 * API URL
	 *
	 * @var $api_url
	 */
	public $api_url;

	/**
	 * API User
	 *
	 * @var $api_user
	 */
	private $api_user;

	/**
	 * API Password
	 *
	 * @var $api_pass
	 */
	private $api_pass;

	/**
	 * Transient Prefix
	 *
	 * @var $transient_prefix
	 */
	private $transient_prefix;

	/**
	 * Test Service
	 *
	 * @var $test_srv
	 */
	private $test_srv;

	/**
	 * Transient Fallback
	 *
	 * @var $transient_fallback
	 */
	private $transient_fallback;

	/**
	 * Image Cache Directory
	 *
	 * @var $image_cache_dir
	 */
	private $image_cache_dir;

	/**
	 * Create a new instance
	 */
	function __construct() {

		$ccbpress_ccb = get_option( 'ccbpress_ccb' );

		$api_user = '';
		if ( isset( $ccbpress_ccb['api_user'] ) ) {
			$api_user = $ccbpress_ccb['api_user'];
		}

		$api_pass = '';
		if ( isset( $ccbpress_ccb['api_pass'] ) ) {
			$api_pass = $ccbpress_ccb['api_pass'];
		}

		$api_prefix = '';
		if ( isset( $ccbpress_ccb['api_prefix'] ) ) {
			$api_prefix = $ccbpress_ccb['api_prefix'];
		}

		$this->api_url            = $this->api_protocol . $api_prefix . $this->api_endpoint;
		$this->api_user           = $api_user;
		$this->api_pass           = $api_pass;
		$this->transient_prefix   = 'ccbp_';
		$this->test_srv           = 'api_status';
		$this->image_cache_dir    = 'ccbpress';
		$this->transient_fallback = CCBPress()->transients;
	}

	/**
	 * Initialize the class
	 *
	 * @return void
	 */
	public function init() {
		$this->actions();
	}

	/**
	 * Action hooks
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function actions() {
		add_action( 'ccbpress_after_get_group_profile_from_id', array( $this, 'cache_image_group_profile_from_id' ), 10, 2 );
		add_action( 'ccbpress_after_get_individual_profile_from_id', array( $this, 'cache_image_individual_profile_from_id' ), 10, 2 );
	}

	/**
	 * Test if we are connected to Church Community Builder
	 *
	 * @since 1.0.0
	 *
	 * @return boolean Answer
	 */
	public function is_connected() {
		$ccbpress_ccb = get_option( 'ccbpress_ccb', array() );
		if ( isset( $ccbpress_ccb['connection_test'] ) && 'success' === $ccbpress_ccb['connection_test'] ) {
			return true;
		}
		return false;
	}

	/**
	 * Build the URL
	 *
	 * @since 1.0.3
	 *
	 * @param  array $query_string Array of query strings.
	 *
	 * @return string
	 */
	public function build_url( $query_string = array() ) {

		if ( ! is_array( $query_string ) ) {
			return false;
		}

		if ( 0 === count( $query_string ) ) {
			return false;
		}

		$url = $this->api_url;
		foreach ( $query_string as $key => $value ) {
			if ( 0 === strlen( is_string( $value ) ? trim( $value ) : ( is_null( $value ) ? '' : $value ) ) ) {
				continue;
			}
			$url = add_query_arg( (string) $key, (string) $value, $url );
		}

		return $url;
	}

	/**
	 * GET data from CCB
	 *
	 * @since 1.0.3
	 *
	 * @param array $args Arguments.
	 *
	 * @return string An XML string containing the data.
	 */
	public function get( $args = array() ) {

		$defaults = array(
			'query_string'   => array(),
			'cache_lifespan' => 60,
			'refresh_cache'  => 0,
			'validate_data'  => true,
		);
		$new_args = wp_parse_args( $args, $defaults );

		// Construct the URL.
		$get_url = $this->build_url( $new_args['query_string'] );

		if ( false === $get_url ) {
			return false;
		}

		$srv = strtolower( $new_args['query_string']['srv'] );

		$transient_name = md5( $get_url );
		$ccb_data       = false;

		// Check the transient cache if the cache is not set to 0.
		if ( $new_args['cache_lifespan'] > 0 && 0 === $new_args['refresh_cache'] ) {
			$ccb_data = $this->transient_fallback->get_transient( $transient_name, 'ccbpress_schedule_get', $new_args );
		}

		// Check for a cached copy in the transient data.
		if ( false !== $ccb_data ) {

			// Load the cached copy from the transient data.
			$ccb_data = @simplexml_load_string( $ccb_data );

			if ( ! $ccb_data ) {
				$this->transient_fallback->delete_transient( $transient_name );
				$ccb_data = '';
			}

			return $ccb_data;
		}

		if ( false === $this->rate_limit_ok( $srv ) ) {
			return false;
		}

		$get_args = array(
			'headers' => array(
				'Authorization' => 'Basic ' . base64_encode( $this->api_user . ':' . $this->api_pass ),
			),
			'timeout' => 300,
		);
		$response = wp_remote_get( $get_url, $get_args );

		// Return false if there was an error.
		if ( is_wp_error( $response ) ) {
			return false;
		}

		$this->update_rate_limit( $response, $srv );

		// Grab the body from the response.
		$ccb_data_raw = wp_remote_retrieve_body( $response );

		// Free up the memory.
		unset( $response );

		$ccb_data = @simplexml_load_string( $ccb_data_raw );

		if ( true === $new_args['validate_data'] && ! $this->is_valid( $ccb_data ) ) {
			return false;
		}

		// Save the transient data according to the cache_lifespan.
		if ( $new_args['cache_lifespan'] > 0 ) {
			$this->transient_fallback->set_transient( $transient_name, $ccb_data_raw, $new_args['cache_lifespan'] );
		}

		// Free up the memory.
		unset( $ccb_data_raw );

		if ( true === $new_args['validate_data'] ) {
			do_action( "ccbpress_after_get_{$srv}", $ccb_data, $new_args );
		}

		return $ccb_data;
	}

	/**
	 * POST data to CCB.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Arguments.
	 *
	 * @return string An XML string containing the data.
	 */
	public function post( $args = array() ) {

		$defaults = array(
			'query_string' => array(),
			'body'         => array(),
		);
		$args     = wp_parse_args( $args, $defaults );

		// Construct the URL.
		$post_url = $this->build_url( $args['query_string'] );

		if ( false === $post_url ) {
			return false;
		}

		$post_args = array(
			'headers' => array(
				'Authorization' => 'Basic ' . base64_encode( $this->api_user . ':' . $this->api_pass ),
			),
			'timeout' => 300,
			'body'    => $args['body'],
		);

		$response = wp_remote_post( $post_url, $post_args );

		if ( ! is_wp_error( $response ) ) {

			// Grab the body from the response.
			$ccb_data = wp_remote_retrieve_body( $response );

			// Free up the memory.
			unset( $response );

			// Convert the XML into an Object.
			$ccb_data = @simplexml_load_string( $ccb_data );

			if ( ! $this->is_valid( $ccb_data ) ) {
				return false;
			}

			$srv = strtolower( $args['query_string']['srv'] );
			do_action( "ccbpress_after_post_{$srv}", $ccb_data, $args );

			return $ccb_data;

		} else {

			return false;

		}
	}

	/**
	 * Check the rate limit
	 *
	 * @return boolean
	 */
	public function rate_limit_ok( $srv ) {
		$ccbpress_rate_limits = get_option( 'ccbpress_rate_limits', array() );

		// Return true if the service has not been requested before.
		if ( ! isset( $ccbpress_rate_limits[ $srv ] ) ) {
			return true;
		}

		if ( isset( $ccbpress_rate_limits[ $srv ]['limit'] ) && isset( $ccbpress_rate_limits[ $srv ]['remaining'] ) ) {
			$limit     = intval( $ccbpress_rate_limits[ $srv ]['limit'] );
			$remaining = intval( $ccbpress_rate_limits[ $srv ]['remaining'] );
			if ( $remaining >= ( $limit / 2 ) ) {
				return true;
			}
		}

		if ( isset( $ccbpress_rate_limits[ $srv ]['reset'] ) ) {
			$reset = intval( $ccbpress_rate_limits[ $srv ]['reset'] );
			if ( time() >= $reset ) {
				return true;
			} else {
				return false;
			}
		}
	}

	/**
	 * Update rate limit option values
	 *
	 * @return void
	 */
	public function update_rate_limit( $response, $srv ) {
		// Get rate-limit headers.
		$ratelimit_limit     = wp_remote_retrieve_header( $response, 'x-ratelimit-limit' );
		$ratelimit_remaining = wp_remote_retrieve_header( $response, 'x-ratelimit-remaining' );
		$ratelimit_reset     = wp_remote_retrieve_header( $response, 'x-ratelimit-reset' );
		$retry_after         = wp_remote_retrieve_header( $response, 'retry-after' );

		$ccbpress_rate_limits = get_option( 'ccbpress_rate_limits', array() );

		$ccbpress_rate_limits[ $srv ] = array(
			'limit'       => $ratelimit_limit,
			'remaining'   => $ratelimit_remaining,
			'reset'       => $ratelimit_reset,
			'retry_after' => $retry_after,
		);

		update_option( 'ccbpress_rate_limits', $ccbpress_rate_limits );
	}

	/**
	 * Test the connection.
	 *
	 * @return string Success/error messages.
	 */
	public function test() {

		// Set the default value for the response.
		$the_response = 'not_set';

		try {

			$test_url = add_query_arg( 'srv', $this->test_srv, $this->api_url );

			// Turn on user error handling.
			libxml_use_internal_errors( true );

			// Clear the error buffer.
			libxml_clear_errors();

			// Define the arguments for the request.
			$args = array(
				'headers' => array(
					'Authorization' => 'Basic ' . base64_encode( $this->api_user . ':' . $this->api_pass ),
				),
				'timeout' => 5,
			);

			// Query the url for a connection response.
			$response = wp_remote_get( $test_url, $args );

			if ( ! is_wp_error( $response ) ) {

				// Grab the body from the response.
				$ccb_data = wp_remote_retrieve_body( $response );

				// Free up the memory.
				unset( $response );

				// Convert the xml into an object.
				$ccb_data = simplexml_load_string( $ccb_data );

				// Check for an error message.
				if ( 'not_set' === $the_response && ! is_null( $ccb_data->response->errors->error ) ) {
					$the_response = $ccb_data->response->errors->error;
				}

				// Check if the response is successful.
				if ( 'not_set' === $the_response && ! is_null( $ccb_data->response->daily_limit ) ) {
					$the_response = 'success';
				}

				if ( 'not_set' === $the_response ) {
					$the_response = esc_html__( 'The API URL appears to be incorrect', 'ccbpress-core' );
				}

				// Free up the memory.
				unset( $ccb_data );

			} else {
				$the_response = $response->get_error_message();
			}
		} catch ( Exception $e ) {
			$the_response = esc_html__( 'Bad connection information', 'ccbpress-core' );
		}

		// Return the response.
		return $the_response;
	}

	/**
	 * Test the connection.
	 *
	 * @param string $api_prefix The API Prefix for CCB.
	 * @param string $api_user   The API User for CCB.
	 * @param string $api_pass   The API Password for CCB.
	 *
	 * @return string Success/error messages.
	 */
	public function test_connection( $api_prefix, $api_user, $api_pass ) {

		if ( '' === trim( $api_prefix ) ) {
			$message = esc_html__( 'Your CCB Website must not be blank.', 'ccbpress-core' );
			add_settings_error(
				'api_url',
				esc_attr( 'settings_updated' ),
				$message,
				'error'
			);
			return $message;
		}

		// Save the real settings.
		$real_api_url  = $this->api_url;
		$real_api_user = $this->api_user;
		$real_api_pass = $this->api_pass;

		// Replace them with these temporary settings.
		$this->api_url  = $this->api_protocol . $api_prefix . $this->api_endpoint;
		$this->api_user = $api_user;
		$this->api_pass = $api_pass;

		$message = $this->test();

		// Put back the real settings.
		$this->api_url  = $real_api_url;
		$this->api_user = $real_api_user;
		$this->api_pass = $real_api_pass;

		switch ( $message ) {
			case 'success':
				$new_message = esc_html__( 'Successfully connected to Church Community Builder. Please check your API Services below.', 'ccbpress-core' );
				$type        = 'updated';
				break;
			default:
				$new_message = $message;
				$type        = 'error';
				break;
		}

		add_settings_error(
			'api_user',
			esc_attr( 'settings_updated' ),
			ucfirst( $new_message ),
			$type
		);

		return $message;
	} // ccbpress_connection_test

	/**
	 * Return the default cache lifespan for a service.
	 *
	 * @param string $srv The CCB service.
	 *
	 * @return int
	 */
	public function cache_lifespan( $srv ) {

		// Add the services to this filter that your add-on uses.
		$services = apply_filters( 'ccbpress_ccb_services', array() );

		foreach ( $services as $service ) {
			if ( $service === $srv ) {
				// Add a filter here if you want to change the default lifespan of 60 minutes.
				return apply_filters( 'ccbpress_cache_' . $service, 60 );
			}
		}

		return 60;
	}

	/**
	 * Validates the data from CCB.
	 *
	 * @param string $ccb_data The data from CCB.
	 *
	 * @return bool true/false.
	 */
	public function is_valid( $ccb_data ) {

		// Make sure that we're dealing with an object.
		if ( ! is_object( $ccb_data ) ) {
			return false;
		}

		// Make sure that the object has properties.
		if ( 0 === count( get_object_vars( $ccb_data ) ) ) {
			return false;
		}

		// Make sure that the request property exists.
		if ( is_null( $ccb_data->request ) ) {
			return false;
		}

		// Make sure that the response property exists.
		if ( is_null( $ccb_data->response ) ) {
			return false;
		}

		// Make sure that there are no errors.
		if ( isset( $ccb_data->response->errors->error ) ) {
			return false;
		}

		// Make sure that there are records in the response.
		$object_vars = get_object_vars( $ccb_data->response );
		foreach ( $object_vars as $key => $value ) {
			foreach ( $ccb_data->response->{$key}->attributes() as $attr_key => $attr_value ) {
				if ( 'count' !== (string) $attr_key ) {
					continue;
				}
				if ( '0' === (string) $attr_value && 'individual_search' !== (string) $ccb_data->response->service ) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Validates the data from CCB.
	 *
	 * @param string $data The data from CCB.
	 *
	 * @return bool true/false.
	 */
	public function is_valid_describe( $data ) {

		if ( ! is_object( $data ) ) {
			return false;
		}

		if ( 0 === count( get_object_vars( $data ) ) ) {
			return false;
		}

		if ( 'describe' !== (string) $data->response->service_action ) {
			return false;
		}

		if ( isset( $data->response->errors->error ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Caches an image from CCB locally
	 *
	 * @param string $image_url The URL to the image.
	 * @param string $image_id  The ID of the image.
	 * @param string $type      The type of image. (group/profile/etc).
	 *
	 * @return bool true/false.
	 */
	public function cache_image( $image_url, $image_id, $type ) {

		$upload_dir = wp_upload_dir();

		// If there is an error, then return false.
		if ( false !== $upload_dir['error'] ) {
			return false;
		}

		$response = wp_remote_get( $image_url );

		if ( ! is_wp_error( $response ) ) {

			if ( ! file_exists( $upload_dir['basedir'] . '/' . $this->image_cache_dir . '/cache' ) ) {
				if ( ! wp_mkdir_p( $upload_dir['basedir'] . '/' . $this->image_cache_dir . '/cache' ) ) {
					return false;
				}
			}

			$image_content = wp_remote_retrieve_body( $response );
			$save_path     = $upload_dir['basedir'] . '/' . $this->image_cache_dir . '/cache/' . $type . '-' . $image_id . '.jpg';

			if ( false === file_put_contents( $save_path, $image_content ) ) {
				return false;
			} else {
				return true;
			}
		} else {
			return false;
		}
	}

	/**
	 * Delete a single image.
	 *
	 * @since 1.3.11
	 *
	 * @param string $image_id The ID of the image.
	 * @param string $type     The type of the image.
	 * @return void
	 */
	public function delete_image( $image_id, $type ) {
		$upload_dir = wp_upload_dir();

		// If there is an error, then exit.
		if ( false !== $upload_dir['error'] ) {
			return;
		}

		$cache_path = $upload_dir['basedir'] . '/' . $this->image_cache_dir . '/cache';

		// If the cache path does not exist, then exit.
		if ( ! file_exists( $cache_path ) ) {
			return;
		}

		$image = "{$cache_path}/{$type}-{$image_id}.jpg";

		if ( is_file( $image ) ) {
			wp_delete_file( $image );
		}
	}

	/**
	 * Purge the image cache
	 *
	 * @since 1.0.2
	 *
	 * @return boolean
	 */
	public function purge_image_cache() {

		$upload_dir = wp_upload_dir();

		// If there is an error, then return false.
		if ( false !== $upload_dir['error'] ) {
			return false;
		}

		$cache_path = $upload_dir['basedir'] . '/' . $this->image_cache_dir . '/cache';

		if ( ! file_exists( $cache_path ) ) {
			return true;
		}

		$images = glob( $cache_path . '/*' );
		foreach ( $images as $image ) {
			if ( is_file( $image ) ) {
				wp_delete_file( $image );
			}
		}

		return true;
	}

	/**
	 * Cache group_profile_from_id image
	 *
	 * @since 1.1.0
	 *
	 * @param object $data Data from Church Community Builder.
	 * @param array  $args Import job item.
	 *
	 * @return void
	 */
	public function cache_image_group_profile_from_id( $data, $args ) {

		if ( ! isset( $data->response->groups->group->image ) ) {
			return;
		}

		$image_url = $data->response->groups->group->image;

		if ( false !== strpos( $image_url, 'group-default' ) ) {
			return;
		}

		$image_id = $data->response->groups->group['id'];
		$this->cache_image( $image_url, $image_id, 'group' );
	}

	/**
	 * Cache individual_profile_from_id image
	 *
	 * @since 1.3.0
	 *
	 * @param object $data Data from Church Community Builder.
	 * @param array  $args Import job item.
	 *
	 * @return void
	 */
	public function cache_image_individual_profile_from_id( $data, $args ) {

		if ( ! isset( $data->response->individuals->individual->image ) ) {
			return;
		}

		$image_url = $data->response->individuals->individual->image;

		if ( false !== strpos( $image_url, 'profile-default' ) ) {
			return;
		}

		$image_id = $data->response->individuals->individual['id'];
		$this->cache_image( $image_url, $image_id, 'individual' );
	}

	/**
	 * Retrieves the URL to a cached image
	 *
	 * @param   string $image_id   The ID of the image.
	 * @param   string $type       The type of image. (group/profile/etc).
	 *
	 * @return string The URL to the image.
	 */
	public function get_image( $image_id, $type ) {

		$upload_dir = wp_upload_dir();

		// If there is an error, then return false.
		if ( false !== $upload_dir['error'] ) {
			return '';
		}

		if ( ! file_exists( $upload_dir['basedir'] . '/' . $this->image_cache_dir . '/cache/' . $type . '-' . $image_id . '.jpg' ) ) {
			return '';
		}

		return $upload_dir['baseurl'] . '/' . $this->image_cache_dir . '/cache/' . $type . '-' . $image_id . '.jpg';
	}

	/**
	 * Check if the form is active.
	 *
	 * @param string $form_id The ID of the form.
	 *
	 * @return bool true/false
	 */
	public function is_form_active( $form_id ) {

		$ccb_data = $this->get(
			array(
				'cache_lifespan' => $this->cache_lifespan( 'form_list' ),
				'query_string'   => array(
					'srv'     => 'form_list',
					'form_id' => $form_id,
				),
			)
		);

		if ( ! $this->is_valid( $ccb_data ) ) {
			return false;
		}

		$current_date = strtotime( (string) date( 'Y-m-d', current_time( 'timestamp' ) ), current_time( 'timestamp' ) );
		$is_valid     = false;

		foreach ( $ccb_data->response->items->form as $form ) {

			if ( (string) $form['id'] === $form_id ) {

				// Check the form date.
				$form_start = strtotime( (string) $form->start, current_time( 'timestamp' ) );
				$form_end   = false;
				if ( '' !== (string) $form->end ) {
					$form_end = strtotime( (string) $form->end, current_time( 'timestamp' ) );
				}

				// Is it before the start date?
				if ( $current_date < $form_start ) {
					$is_valid = false;
					break;
				}

				// Is there and end date and is it after that end date?
				if ( false !== $form_end && $current_date > $form_end ) {
					$is_valid = false;
					break;
				}

				// Has the form been published?
				if ( 'false' === (string) $form->published ) {
					$is_valid = false;
					break;
				}

				// Has the form been archived?
				if ( 'true' === (string) $form->archived ) {
					$is_valid = false;
					break;
				}

				// Has the form expired?
				if ( 'Expired' === (string) $form->status ) {
					$is_valid = false;
					break;
				}

				$is_valid = true;
				break;

			}
		}

		// Return the data.
		return $is_valid;
	}
}
