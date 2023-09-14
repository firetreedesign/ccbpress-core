<?php
/**
 * Admin REST API
 *
 * @package CCBPress_Core
 * @since 1.3.4
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin REST API class
 */
class CCBPress_Admin_REST_API {

	/**
	 * Initialize the class
	 *
	 * @since 1.3.4
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'rest_api_init', __CLASS__ . '::rest_api_init' );
	}

	/**
	 * Initialize the endpoints
	 *
	 * @since 1.2.0
	 *
	 * @return void
	 */
	public static function rest_api_init() {

		register_rest_route(
			'ccbpress/v1',
			'/admin/reschedule_cron_jobs',
			array(
				'methods'             => 'POST',
				'callback'            => __CLASS__ . '::reschedule_cron_jobs',
				'permission_callback' => __CLASS__ . '::permission_callback',
			)
		);

		register_rest_route(
			'ccbpress/v1',
			'/admin/purge_image_cache',
			array(
				'methods'             => 'POST',
				'callback'            => __CLASS__ . '::purge_image_cache',
				'permission_callback' => __CLASS__ . '::permission_callback',
			)
		);

		register_rest_route(
			'ccbpress/v1',
			'/admin/purge_transient_cache',
			array(
				'methods'             => 'POST',
				'callback'            => __CLASS__ . '::purge_transient_cache',
				'permission_callback' => __CLASS__ . '::permission_callback',
			)
		);

		register_rest_route(
			'ccbpress/v1',
			'/admin/check_api_services',
			array(
				'methods'             => 'POST',
				'callback'            => __CLASS__ . '::check_api_services',
				'permission_callback' => __CLASS__ . '::permission_callback',
			)
		);

		register_rest_route(
			'ccbpress/v1',
			'/admin/reset_import',
			array(
				'methods'             => 'POST',
				'callback'            => __CLASS__ . '::reset_import',
				'permission_callback' => __CLASS__ . '::permission_callback',
			)
		);

		register_rest_route(
			'ccbpress/v1',
			'/admin/start_import',
			array(
				'methods'             => 'POST',
				'callback'            => __CLASS__ . '::start_import',
				'permission_callback' => __CLASS__ . '::permission_callback',
			)
		);

		register_rest_route(
			'ccbpress/v1',
			'/admin/last_import',
			array(
				'methods'             => 'POST',
				'callback'            => __CLASS__ . '::last_import',
				'permission_callback' => __CLASS__ . '::permission_callback',
			)
		);

		register_rest_route(
			'ccbpress/v1',
			'/admin/import_status',
			array(
				'methods'             => 'POST',
				'callback'            => __CLASS__ . '::import_status',
				'permission_callback' => __CLASS__ . '::permission_callback',
			)
		);

		register_rest_route(
			'ccbpress/v1',
			'/admin/groups',
			array(
				'methods'             => 'POST',
				'callback'            => __CLASS__ . '::rest_api_groups',
				'permission_callback' => __CLASS__ . '::permission_callback',
			)
		);

		register_rest_route(
			'ccbpress/v1',
			'/admin/group/(?P<id>\d+)',
			array(
				'methods'             => 'POST',
				'callback'            => __CLASS__ . '::rest_api_group',
				'permission_callback' => __CLASS__ . '::permission_callback',
			)
		);

		register_rest_route(
			'ccbpress/v1',
			'/admin/is-form-active/(?P<id>\d+)',
			array(
				'methods'             => 'POST',
				'callback'            => __CLASS__ . '::rest_api_is_form_active',
				'permission_callback' => __CLASS__ . '::permission_callback',
			)
		);

	}

	/**
	 * Check the user permissions
	 *
	 * @return boolean
	 */
	public static function permission_callback() {
		return __return_true();
	}

	/**
	 * @since 1.5.0
	 * @param WP_REST_Request $request Request object.
	 * @return array
	 */
	public static function reschedule_cron_jobs( WP_REST_Request $request ) {

		CCBPress_Core::unschedule_cron();
		CCBPress_Core::schedule_cron();
		return new WP_REST_Response( array( 'result' => 'success' ), 200 );

	}

	/**
	 * Purge the image cache
	 *
	 * @since 1.3.4
	 * @param  WP_REST_Request $request Request object.
	 * @return array
	 */
	public static function purge_image_cache( WP_REST_Request $request ) {

		CCBPress_Purge_Cache::purge_images();
		return new WP_REST_Response( array( 'result' => 'success' ), 200 );

	}

	/**
	 * Purge the transient cache
	 *
	 * @since 1.3.4
	 * @param  WP_REST_Request $request Request object.
	 * @return array
	 */
	public static function purge_transient_cache( WP_REST_Request $request ) {

		CCBPress_Purge_Cache::purge_transients();
		return new WP_REST_Response( array( 'result' => 'success' ), 200 );

	}

	/**
	 * Check CCB API Services
	 *
	 * @since 1.3.4
	 * @param WP_REST_Request $request Request object.
	 * @return array
	 */
	public static function check_api_services( WP_REST_Request $request ) {

		$services = apply_filters( 'ccbpress_ccb_services', array() );
		sort( $services );

		if ( 0 === count( $services ) ) {
			return new WP_Error( 'no_services', 'There are no services registered with Church Data Connect for Church Community Builder.', array( 'status' => 404 ) );
		}

		$results = array();

		foreach ( $services as $service ) {

			$ccb_data = CCBPress()->ccb->get(
				array(
					'cache_lifespan' => 0,
					'refresh_cache'  => 1,
					'validate_data'  => false,
					'query_string'   => array(
						'srv'          => $service,
						'describe_api' => '1',
					),
				)
			);

			$service_result   = array();
			$service_result[] = CCBPress()->ccb->is_valid_describe( $ccb_data );
			$service_result[] = $ccb_data;

			if ( true === $service_result[0] ) {
				$results[] = array(
					'service' => $service,
					'status'  => esc_html__( 'Passed', 'ccbpress-core' ),
				);
			} else {
				if ( isset( $ccb_data->response->errors->error ) ) {
					$results[] = array(
						'service' => $service,
						'status'  => (string) $ccb_data->response->errors->error,
					);
				} else {
					$results[] = array(
						'service' => $service,
						'status'  => esc_html__( 'There was an error with this service', 'ccbpress-core' ),
					);
				}
			}

			// Free up the memory.
			unset( $service_result );
		}

		return new WP_REST_Response( $results, 200 );

	}

	/**
	 * Reset Import
	 *
	 * @since 1.3.4
	 * @param WP_REST_Request $request Request object.
	 * @return array
	 */
	public static function reset_import( WP_REST_Request $request ) {
		update_option( 'ccbpress_cancel_import', 'yes' );
		CCBPress_Import::reset();
		CCBPress_Import::reschedule();
		return new WP_REST_Response( array( 'result' => 'success' ), 200 );
	}

	/**
	 * Start Import
	 *
	 * @since 1.3.4
	 * @param WP_REST_Request $request Request object.
	 * @return array
	 */
	public static function start_import( WP_REST_Request $request ) {
		CCBPress_Import::run();
		return new WP_REST_Response( array( 'result' => 'success' ), 200 );
	}

	/**
	 * Last Import
	 *
	 * @since 1.3.4
	 * @param WP_REST_Request $request Request object.
	 * @return array
	 */
	public static function last_import( WP_REST_Request $request ) {
		$last_import = get_option( 'ccbpress_last_import', 'Never' );
		$response    = '';
		if ( 'Never' === $last_import ) {
			$response = $last_import;
		} else {
			$response = esc_html( human_time_diff( strtotime( 'now', time() ), strtotime( $last_import, time() ) ) . ' ago' );
		}
		return new WP_REST_Response( array( 'last_import' => $response ), 200 );
	}

	/**
	 * Import Status
	 *
	 * @since 1.3.4
	 * @param WP_REST_Request $request Request object.
	 * @return array
	 */
	public static function import_status( WP_REST_Request $request ) {

		$status   = array();
		$progress = get_option( 'ccbpress_import_in_progress', false );

		if ( false === $progress ) {
			return new WP_REST_Response( array( 'import_status' => 'done' ), 200 );
		}

		array_push(
			$status,
			array(
				'text'    => $progress,
				'element' => 'strong',
			)
		);
		array_push(
			$status,
			array(
				'text'    => esc_html__( 'Import is running in the background. Leaving this page will not interrupt the process.', 'ccbpress-core' ),
				'element' => 'i',
			)
		);

		return new WP_REST_Response( array( 'import_status' => $status ), 200 );
	}

	/**
	 * Return groups from CCB
	 *
	 * @since 1.2.0
	 * @param  WP_REST_Request $request Request object.
	 * @return array
	 */
	public static function rest_api_groups( WP_REST_Request $request ) {
		$groups_array = array();

		if ( has_filter( 'ccbpress_rest_api_admin_groups' ) ) {
			$ccb_groups = apply_filters( 'ccbpress_rest_api_admin_groups', array() );

			foreach ( $ccb_groups as $group ) {
				$groups_array[] = array(
					'id'   => (string) $group['id'],
					'name' => (string) $group->name,
				);
			}
		} else {
			$ccb_groups = CCBPress()->ccb->get(
				array(
					'cache_lifespan' => 2880,
					'query_string'   => array(
						'srv'                  => 'group_profiles',
						'include_participants' => '0',
						'include_image_link'   => '0',
						'modified_since'       => (string) date( 'Y-m-d', strtotime( '-5 months' ) ),
					),
				)
			);

			if ( ! $ccb_groups ) {
				return new WP_Error( 'ccbpress-core', 'No groups found.' );
			}

			foreach ( $ccb_groups->response->groups->group as $group ) {
				$groups_array[] = array(
					'id'   => (string) $group['id'],
					'name' => (string) $group->name,
				);
			}
		}

		unset( $ccb_groups );

		usort(
			$groups_array,
			function( $a, $b ) {
				return strcmp( $a['name'], $b['name'] );
			}
		);

		return $groups_array;
	}

	/**
	 * Return group from CCB
	 *
	 * @since 1.3.0
	 * @param  WP_REST_Request $request Request object.
	 * @return array
	 */
	public static function rest_api_group( WP_REST_Request $request ) {

		$id = $request->get_param( 'id' );

		if ( is_null( $id ) ) {
			return new WP_Error( 'ccbpress-core', 'No group ID.' );
		}

		if ( has_filter( 'ccbpress_rest_api_admin_group' ) ) {
			$data = apply_filters( 'ccbpress_rest_api_admin_group', null, $id );
		} else {
			$data = CCBPress()->ccb->get(
				array(
					'cache_lifespan' => CCBPress()->ccb->cache_lifespan( 'group_profile_from_id' ),
					'query_string'   => array(
						'srv'                => 'group_profile_from_id',
						'id'                 => $id,
						'include_image_link' => '1',
					),
				)
			);
		}

		if ( ! $data ) {
			return new WP_Error( 'ccbpress-core', 'Group not found.' );
		}

		$new_data       = new StdClass();
		$new_data->data = $data->response->groups->group;

		// Get the cached group image.
		$new_data->image = CCBPress()->ccb->get_image( $id, 'group' );

		// Get their profile image from their user profile.
		$group_main_leader_profile = CCBPress()->ccb->get(
			array(
				'cache_lifespan' => CCBPress()->ccb->cache_lifespan( 'individual_profile_from_id' ),
				'query_string'   => array(
					'srv'              => 'individual_profile_from_id',
					'individual_id'    => (string) $new_data->data->main_leader['id'],
					'include_inactive' => 0,
				),
			)
		);

		$new_data->data->main_leader->image = CCBPress()->ccb->get_image( $new_data->data->main_leader['id'], 'individual' );

		return new WP_REST_Response( $new_data, 200 );
	}

	/**
	 * Return if form is active
	 *
	 * @since 1.3.0
	 * @param  WP_REST_Request $request Request object.
	 * @return boolean
	 */
	public static function rest_api_is_form_active( WP_REST_Request $request ) {
		$id = $request->get_param( 'id' );

		if ( is_null( $id ) ) {
			return new WP_Error( 'ccbpress-core', 'No form ID.' );
		}

		return new WP_REST_Response( CCBPress()->ccb->is_form_active( (string) $id ), 200 );
	}

}

CCBPress_Admin_REST_API::init();
