<?php
/**
 * Church Community Builder Connection
 *
 * @package     CCBPress_Core
 * @copyright   Copyright (c) 2015, FireTree Design, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class CCBPress_Connection {

	private $api_protocol = 'https://';
	private $api_endpoint = '.ccbchurch.com/api.php';
	public $api_url;
	private $api_user;
	private $api_pass;
	private $transient_prefix;
	private $test_srv;
	private $transient_fallback;
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

		$this->api_url				= $this->api_protocol . $api_prefix . $this->api_endpoint;
		$this->api_user				= $api_user;
		$this->api_pass				= $api_pass;
        $this->transient_prefix		= 'ccbpress_';
		$this->test_srv				= 'api_status';
		$this->image_cache_dir		= 'ccbpress';
		$this->transient_fallback	= CCBPress()->transients;

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
		if ( isset( $ccbpress_ccb['connection_test']) && 'success' === $ccbpress_ccb['connection_test'] ) {
			return TRUE;
		}
		return FALSE;
	}

    /**
	 * GET data from CCB.
	 *
	 * @since 1.0.0
	 *
	 * @param	string	$api_url					The API URL to request the data from.
	 * @param	string	$username					The username to send to the API.
	 * @param	string	$password					The password to send to the API.
	 * @param	number	$cache_lifespan	Optional.	The cache lifespan in minutes.
	 *
	 * @return	string	An XML string containing the data.
	 */
	public function get( $get_url, $cache_lifespan = 0 ) {

		//$get_url = $this->api_url . $srv;
		$transient_name = md5( $get_url );
		$ccb_data = false;

		if ( $cache_lifespan > 0 ) {
			$ccb_data = $this->transient_fallback->get_transient( $transient_name, 'ccbpress_schedule_get', array( $get_url, $cache_lifespan, $this->api_user, $this->api_pass, $this->transient_prefix ) );
		}

		// Check for a cached copy in the transient data
		if ( false === $ccb_data ) {

			$args = array(
				'headers'	=> array(
					'Authorization' => 'Basic ' . base64_encode( $this->api_user . ':' . $this->api_pass )
					),
				'timeout'	=> 300,
				);

			$response = wp_remote_get( $get_url, $args );
			if ( ! is_wp_error( $response ) ) {

				// Grab the body from the response
				$ccb_data = wp_remote_retrieve_body( $response );

				// Free up the memory
				unset( $response );

				// Save the transient data according to the $cache_lifespan
				if ( $cache_lifespan > 0 ) {
					$this->transient_fallback->set_transient( $transient_name, $ccb_data, $cache_lifespan );
				}

				$ccb_data = @simplexml_load_string( $ccb_data );

				if ( $ccb_data ) {

					$this->find_images( $ccb_data );

				} else {

					$this->transient_fallback->delete_transient( $transient_name );

					$ccb_data = '';

				}

			}

		} else {

			// Load the cached copy from the transient data
			$ccb_data = @simplexml_load_string( $ccb_data );

			if ( ! $ccb_data ) {

				$this->transient_fallback->delete_transient( $transient_name );

				$ccb_data = '';

			}

		}

		return $ccb_data;

	}

    /**
	 * POST data to CCB.
	 *
	 * @since 1.0.0
	 *
	 * @param	string	$api_url	The API URL to request the data from.
	 * @param	string	$username	The username to send to the API.
	 * @param	string	$password	The password to send to the API.
	 * @param	array	$body		The array of data to send to the service.
	 *
	 * @return	string	An XML string containing the data.
	 */
	public function post( $post_url, $body ) {

		//$post_url = $this->api_url . $srv;

		$args = array(
			'headers'	=> array(
				'Authorization' => 'Basic ' . base64_encode( $this->api_user . ':' . $this->api_pass )
				),
			'timeout'	=> 300,
			'body'		=> $body,
			);

		$response = wp_remote_post( $post_url, $args );

		if ( ! is_wp_error( $response ) ) {

			// Grab the body from the response
			$ccb_data = wp_remote_retrieve_body( $response );

			// Free up the memory
			unset( $response );

			// Convert the XML into an Object
			$ccb_data = simplexml_load_string( $ccb_data );

			return $ccb_data;

		} else {

			return '';

		}

	}

    /**
	 * Test the connection.
	 *
	 * @param	string	$ccb_api_url		The API URL for CCB.
	 * @param	string	$ccb_api_user	The API User for CCB.
	 * @param	string	$ccb_api_pass	The API Password for CCB.
	 *
	 * @return 	string	Success/error messages.
	 */
	public function test() {

		// Set the default value for the response
		$the_response = 'not_set';

		try {

			$test_url = add_query_arg( 'srv', $this->test_srv, $this->api_url );

			// Turn on user error handling
			libxml_use_internal_errors( true );

			// Clear the error buffer
			libxml_clear_errors();

			// Define the arguments for the request
			$args = array(
				'headers'	=> array(
					'Authorization' => 'Basic ' . base64_encode( $this->api_user . ':' . $this->api_pass )
					),
				'timeout'	=> 5,
				);

			// Query the url for a connection response
			$response = wp_remote_get( $test_url, $args );

			if ( ! is_wp_error( $response ) ) {

				// Grab the body from the response
				$ccb_data = wp_remote_retrieve_body( $response );

				// Free up the memory
				unset( $response );

				// Convert the xml into an object
				$ccb_data = simplexml_load_string( $ccb_data );

				// Check if the response is successful
				if ( $ccb_data->response->daily_limit != '' ) {

					// The response was successful
					$the_response = 'success';

				// Check for an error message
				} elseif ( $ccb_data->response->errors->error != '' ) {

					// Return the error message
					$the_response = $ccb_data->response->errors->error;

				} else {

					// If no error message, then the URL may be bad
					$the_response = __('The API URL appears to be incorrect', 'ccbpress-core');

				}

				// Free up the memory
				unset( $ccb_data );

			} else {

				$the_response = $response->get_error_message();

			}

		} catch ( Exception $e ) {

			$the_response = __('Bad connection information', 'ccbpress-core');

		}

		// Return the response
		return $the_response;

	}

	/**
	 * Test the connection.
	 *
	 * @param	string	$api_url	The API URL for CCB.
	 * @param	string	$api_user	The API User for CCB.
	 * @param	string	$api_pass	The API Password for CCB.
	 *
	 * @return 	string	Success/error messages.
	 */
	public function test_connection( $api_prefix, $api_user, $api_pass ) {

		// Save the real settings
		$real_api_url	= $this->api_url;
		$real_api_user	= $this->api_user;
		$real_api_pass	= $this->api_pass;

		// Replace them with these temporary settings
		$this->api_url	= $this->api_protocol . $api_prefix . $this->api_endpoint;
		$this->api_user	= $api_user;
		$this->api_pass	= $api_pass;

		$message = $this->test();

		// Put back the real settings
		$this->api_url	= $real_api_url;
		$this->api_user	= $real_api_user;
		$this->api_pass	= $real_api_pass;

		switch( $message ) {
			case 'success':
				$new_message = __('Successfully connected to Church Community Builder', 'ccbpress-core');
				$type = 'updated';
				break;
			default:
				$new_message = $message;
				$type = 'error';
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
	 * @param	string	$srv	The CCB service.
	 *
	 * @return	int
	 */
	public function cache_lifespan( $srv ) {

        // Add the services to this filter that your add-on uses
        $services = apply_filters( 'ccbpress_ccb_services', array() );

        foreach( $services as $service ) {
            if ( $service === $srv ) {
                // Add a filter here if you want to change the default lifespan of 60 minutes
                return apply_filters( 'ccbpress_cache_' . $service, 60 );
            }
        }

        return 60;

	}

    /**
	 * Validates the data from CCB.
	 *
	 * @param	string	$ccb_data	The data from CCB.
	 *
	 * @return 	bool	true/false.
	 */
	public function is_valid( $ccb_data ) {

		if ( is_object( $ccb_data ) && count( get_object_vars( $ccb_data ) ) > 0 ) {

            if ( ! is_null( $ccb_data->request ) && ! is_null( $ccb_data->response ) && is_null( $ccb_data->response->errors->error ) ) {

                return true;

            }

		}

		return false;

	}

	/**
	 * Validates the data from CCB.
	 *
	 * @param	string	$ccb_data	The data from CCB.
	 *
	 * @return 	bool	true/false.
	 */
	public function is_valid_describe( $ccb_data ) {

		if ( is_object( $ccb_data ) && count( get_object_vars( $ccb_data ) ) > 0 ) {

			if ( $ccb_data->response->service_action == 'describe' && $ccb_data->response->errors->error == '' ) {
				return true;
			}

		}

		return false;

	}

	public function find_images( $ccb_data ) {

		if ( isset( $ccb_data->request->parameters->argument ) ) {

			foreach ( $ccb_data->request->parameters->argument as $argument ) {

				if ( $argument['name'] == 'srv' ) {

					switch ( $argument['value'] ) {

						case 'group_profile_from_id':

							if ( isset( $ccb_data->response->groups->group->image ) && isset( $ccb_data->response->groups->group['id'] ) ) {
								$image_url = $ccb_data->response->groups->group->image;
								$image_id = $ccb_data->response->groups->group['id'];
								$this->cache_image( $image_url, $image_id, 'group' );
							}

							break;

					}

				}

			}

		}

	}

    /**
	 * Caches an image from CCB locally
	 *
	 * @param	string	$image_url	The URL to the image.
     * @param   string  $image_id   The ID of the image.
     * @param   string  $type       The type of image. (group/profile/etc)
	 *
	 * @return 	bool	true/false.
	 */
    public function cache_image( $image_url, $image_id, $type ) {

		$upload_dir = wp_upload_dir();

		// If there is an error, then return false
		if ( false != $upload_dir['error'] ) {
			return false;
		}

		$response = wp_remote_get( $image_url );

		if ( ! is_wp_error( $response ) ) {

			if ( ! file_exists( $upload_dir['basedir'] . '/' . $this->image_cache_dir . '/cache' ) ) {
				if( ! wp_mkdir_p( $upload_dir['basedir'] . '/' . $this->image_cache_dir . '/cache' ) ) {
					return false;
				}
			}

			$image_content = wp_remote_retrieve_body( $response );
			$save_path = $upload_dir['basedir'] . '/' . $this->image_cache_dir . '/cache/' . $type . '-' . $image_id . '.jpg';

			if ( false === file_put_contents( $save_path , $image_content ) ) {
				return false;
			} else {
				return true;
			}

		} else {
			return false;
		}

	}

    /**
	 * Retrieves the URL to a cached image
	 *
     * @param   string  $image_id   The ID of the image.
     * @param   string  $type       The type of image. (group/profile/etc)
	 *
	 * @return 	string	The URL to the image.
	 */
    public function get_image( $image_id, $type ) {

		$upload_dir = wp_upload_dir();

		// If there is an error, then return false
		if ( false != $upload_dir['error'] ) {
			return '';
		}

		return $upload_dir['baseurl'] . '/' . $this->image_cache_dir . '/cache/' . $type . '-' . $image_id . '.jpg';

	}

	/**
	 * Check if the form is active.
	 *
	 * @param	string	$ccb_data	The data from CCB.
	 * @param	string	$form_id	The ID of the form.
	 *
	 * @return	bool	true/false
	 */
	public function is_form_active( $form_id ) {

		$args = array(
			'form_id' => $form_id
		);
		$ccb_data = $this->form_list( $args );

		$current_date = strtotime( (string)date( 'Y-m-d' ) );
		$is_valid = false;

		foreach ( $ccb_data->response->items->form as $form ) {

			//return $form;

			if ( $form['id'] == $form_id ) {

				// Check the form date
				$form_start	= strtotime( $form->start );
				if ( $form->end == '' ) {
					$form_end = $current_date;
				} else {
					$form_end = strtotime( $form->end );
				}

				if ( $current_date < $form_start || $current_date > $form_end ) {
					return FALSE;
				}


				// Is the form not public?
				if ( $form->public == 'false' ) {
					$is_valid = false;
					break;
				}

				// Has the form been published?
				if ( $form->published == 'false' ) {
					$is_valid = false;
					break;
				}

				// Has the form expired?
				if ( $form->status == 'Expired' ) {
					$is_valid = false;
					break;
				}

				$is_valid = true;
				break;

			}

		}

		// Return the data
		return $is_valid;

	}

	/**
	 * Returns the API Status.
	 *
	 * @return	string/array	An XML string containing the data.
	 */
	public function api_status( $args ) {

		$defaults = array(
			'describe_api'		=> NULL,
			'cache_lifespan'	=> $this->cache_lifespan( 'api_status' ), // In minutes.
		);

		$args = wp_parse_args( $args, $defaults );

		try {

			$url = add_query_arg( 'srv', 'api_status', $this->api_url );

			$ccb_data = $this->get( $url, $args['cache_lifespan'] );

			if ( $this->is_valid( $ccb_data ) ) {

				// Return the data
				return $ccb_data;

			} else {

				// Return the data
				return false;

			}

		} catch ( Exception $e ) {

			// Return the data
			return false;

		}

	}

	/**
	 * Returns the event profiles
	 *
	 * @since 1.0.0
	 *
	 * @param  array $args The arguments for the API request
	 *
	 * @return varies       Either the data object or FALSE
	 */
	public function event_profiles( $args ) {

		$defaults = array(
			'include_guest_list'	=> '0', // 1 = yes, 0 = no
			'page'					=> null,
			'per_page'				=> null,
			'modified_since'		=> null,	// Date. Example: YYYY-MM-DD.
			'cache_lifespan'		=> $this->cache_lifespan( 'event_profiles' ),
		);

		$args = wp_parse_args( $args, $defaults );

		try {

			$url = add_query_arg( 'srv', 'event_profiles', $this->api_url );
			$url = add_query_arg( 'include_guest_list', $args['include_guest_list'], $url );

			if ( $args['page'] )  {
				$url = add_query_arg( 'page', $args['page'], $url );
			}

			if ( $args['per_page'] )  {
				$url = add_query_arg( 'per_page', $args['per_page'], $url );
			}

			if ( $args['modified_since'] ) {
				$url = add_query_arg( 'modified_since', $args['modified_since'], $url );
			}
			$ccb_data = $this->get( $url, $args['cache_lifespan'] );

			if ( $this->is_valid( $ccb_data ) ) {

				return $ccb_data;

			} else {

				return false;

			}

		} catch ( Exception $e ) {
			return false;

		}

	}

	/**
	 * Returns a list of forms.
	 *
	 * @return	string/array	An XML string containing the data.
	 */
	public function form_list( $args ) {

		$defaults = array(
			'form_id'			=> NULL,
			'campus_id'			=> NULL,
			'modified_since'	=> NULL,	// Date. Example: YYYY-MM-DD.
			'describe_api'		=> NULL,
			'cache_lifespan'	=> $this->cache_lifespan( 'form_list' ),	// In minutes.
		);

		$args = wp_parse_args( $args, $defaults );

		try {

			// Retrieve the data from CCB
			$url = add_query_arg( 'srv', 'form_list', $this->api_url );
			if ( $args['form_id'] ) { $url = add_query_arg( 'form_id', $args['form_id'], $url ); }
			if ( $args['campus_id'] ) { $url = add_query_arg( 'campus_id', $args['campus_id'], $url ); }
			if ( $args['modified_since'] ) { $url = add_query_arg( 'modified_since', $args['modified_since'], $url ); }
			if ( $args['describe_api'] ) { $url = add_query_arg( 'describe_api', $args['describe_api'], $url ); }

			$ccb_data = $this->get( $url, $args['cache_lifespan'] );

			//return $ccb_data;

			if ( $this->is_valid( $ccb_data ) ) {

				// Return the data
				return $ccb_data;

			} else {

				// Return the data
				return FALSE;

			}

		} catch ( Exception $e ) {

			// Return the data
			return FALSE;

		}

	}

	/**
	 * Retrieve a group profile.
	 *
	 * @param	array	$args	An array of the arguments.
	 *
	 * @return	string	An XML string containing the data.
	 */
	public function group_profile_from_id( $args ) {

		$defaults = array(
			'id'				=> NULL,	// Integer (Required).
			'describe_api'		=> 0,
			'cache_lifespan'	=> $this->cache_lifespan( 'group_profile_from_id' ), // In minutes.
		);

		$args = wp_parse_args( $args, $defaults );

		try {

			// Retrieve the data from CCB
			$url = add_query_arg( 'srv', 'group_profile_from_id', $this->api_url );
			$url = add_query_arg( 'id', $args['id'], $url );
			if ( 1 === $args['describe_api'] ) { $url = add_query_arg( 'describe_api', $args['describe_api'], $url ); }
			$ccb_data = $this->get( $url, $args['cache_lifespan'] );

			if ( $this->is_valid( $ccb_data ) ) {

				// Return the data
				return $ccb_data;

			} else {

				// Return the data
				return FALSE;

			}

		} catch ( Exception $e ) {

			// Return the data
			return FALSE;

		}

	}

	/**
	 * Returns group_profiles
	 *
	 * @since 1.0.0
	 *
	 * @param  array $args An array of the arguments.
	 *
	 * @return object       An object containing the search results.
	 */
	public function group_profiles( $args ) {

		$defaults = array(
			'include_participants'	=> '0',		// 1 = yes, 0 = no
			'include_image_link'	=> '0',
			'page'					=> null,
			'per_page'				=> null,
			'campus_id'				=> null,
			'modified_since'		=> (string)date( 'Y-m-d', strtotime('-6 months') ),	// Date. Example: YYYY-MM-DD.
			'cache_lifespan'		=> $this->cache_lifespan( 'group_profiles' ),
		);

		$args = wp_parse_args( $args, $defaults );

		try {

			$url = add_query_arg( 'srv', 'group_profiles', $this->api_url );
			$url = add_query_arg( 'include_participants', $args['include_participants'], $url );
			$url = add_query_arg( 'include_image_link', $args['include_image_link'], $url );
			$url = add_query_arg( 'modified_since', $args['modified_since'], $url );

			if ( $args['page'] )  {
				$url = add_query_arg( 'page', $args['page'], $url );
			}

			if ( $args['per_page'] )  {
				$url = add_query_arg( 'per_page', $args['per_page'], $url );
			}

			if ( $args['campus_id'] )  {
				$url = add_query_arg( 'campus_id', $args['campus_id'], $url );
			}

			$ccb_data = $this->get( $url, $args['cache_lifespan'] );

			if ( $this->is_valid( $ccb_data ) ) {

				// Return the data
				return $ccb_data;

			} else {

				// Return the data
				return FALSE;

			}

		} catch ( Exception $e ) {

			return FALSE;

		}

	}

	/**
	 * Retrieve an individual profile.
	 *
	 * @param	array	$args	An array of the arguments.
	 *
	 * @return	string	An XML string containing the data.
	 */
	public function individual_profile_from_id( $args ) {

		$defaults = array(
			'individual_id'		=> '',		// The id of the profile to retrieve.
			'include_inactive'	=> 0,	// Optional. Boolean.
			'cache_lifespan'	=> $this->cache_lifespan( 'individual_profile_from_id' ),	// In minutes.
		);

		$args = wp_parse_args( $args, $defaults );

		try {

			// Retrieve the data from CCB
			$url = add_query_arg( 'srv', 'individual_profile_from_id', $this->api_url );
			$url = add_query_arg( 'individual_id', $args['individual_id'], $url );
			$url = add_query_arg( 'include_inactive', $args['include_inactive'], $url );
			$ccb_data = $this->get( $url, $args['cache_lifespan'] );

			if ( $this->is_valid( $ccb_data ) ) {

				// Return the data
				return $ccb_data;

			} else {

				// Return the data
				return FALSE;

			}

		} catch ( Exception $e ) {

			// Return the data
			return FALSE;

		}

	}

}
