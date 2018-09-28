<?php
/**
 * Group Info Block
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since 	1.0.0
 * @package CGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CCBPress Core Blocks class
 */
class CCBPress_Core_Group_Info_Block {

    /**
	 * Initialize the class
	 *
	 * @since 1.2.0
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'rest_api_init',                __CLASS__ . '::rest_api_init' );
		add_action( 'enqueue_block_editor_assets',  __CLASS__ . '::enqueue_block_editor_assets' );
		add_action( 'init',                         __CLASS__ . '::block_init' );
    }
    
    public static function block_init() {
		if ( ! function_exists( 'register_block_type' ) ) {
            return;
        }
            
        register_block_type( 'ccbpress/group-info', array(
            'render_callback' => __CLASS__ . '::render',
        ) );
    }
    
    /**
	 * Initialize the endpoints
	 *
	 * @since 1.2.0
	 *
	 * @return void
	 */
	public static function rest_api_init() {
		
		register_rest_route( 'ccbpress/v1', '/admin/groups', array(
	        'methods' => 'POST',
			'callback' => __CLASS__ . '::rest_api_groups',
		) );

		register_rest_route( 'ccbpress/v1', '/admin/group/(?P<id>\d+)', array(
			'methods' => 'POST',
			'callback' => __CLASS__ . '::rest_api_group',
		) );
		
    }
    
    /**
	 * Return groups from CCB
	 *
	 * @since 1.2.0
	 * @param  WP_REST_Request $request Request object.
	 * @return array
	 */
	public static function rest_api_groups( WP_REST_Request $request ) {
		if ( has_filter( 'ccbpress_rest_api_admin_groups' ) ) {
			$ccb_groups = apply_filters( 'ccbpress_rest_api_admin_groups', array() );
		} else {
			$ccb_groups = CCBPress()->ccb->get( array(
				'cache_lifespan'	=> 2880,
				'query_string'		=> array(
					'srv'					=> 'group_profiles',
					'include_participants'	=> '0',
					'include_image_link'	=> '0',
					'modified_since'		=> (string) date( 'Y-m-d', strtotime( '-5 months' ) ),
				),
			) );
		}

		if ( ! $ccb_groups ) {
			return new WP_Error( 'ccbpress-core', 'No groups found.' );
		}

		$groups_array = array();

		foreach ( $ccb_groups->response->groups->group as $group ) {
			$groups_array[] = array( 'id' => (string) $group['id'], 'name' => (string) $group->name );
		}
		unset( $ccb_groups );

		usort($groups_array, function($a, $b) {
			return strcmp($a["name"], $b["name"]);
		});

		return $groups_array;
	}

	/**
	 * Return group from CCB
	 *
	 * @since 1.2.0
	 * @param  WP_REST_Request $request Request object.
	 * @return array
	 */
	public static function rest_api_group( WP_REST_Request $request ) {

		$id = $request->get_param( 'id' );

		if ( is_null( $id ) ) {
			return new WP_Error( 'ccbpress-core', 'No group ID.' );
		}

		if ( has_filter( 'ccbpress_rest_api_admin_group' ) ) {
			$$data = apply_filters( 'ccbpress_rest_api_admin_group', null, $id );
		} else {
			$data = CCBPress()->ccb->get( array(
				'cache_lifespan'	=> CCBPress()->ccb->cache_lifespan( 'group_profile_from_id' ),
				'query_string'		=> array(
					'srv'					=> 'group_profile_from_id',
					'id'					=> $id,
					'include_image_link'	=> '1',
				),
			) );
		}

		if ( ! $data ) {
			return new WP_Error( 'ccbpress-core', 'Group not found.' );
		}

		$new_data = new StdClass();
		$new_data->data = $data->response->groups->group;

		// Get the cached group image.
		$new_data->image = CCBPress()->ccb->get_image( $id, 'group' );

		return new WP_REST_Response( $new_data, 200 );
    }
    
    /**
	 * Enqueue Gutenberg block assets for both frontend + backend.
	 *
	 * @since 1.2.0
	 *
	 * @return void
	 */
	public static function enqueue_block_editor_assets() {

		// Scripts.
		wp_localize_script(
			'ccbpress-core-block-js',
			'ccbpress_core_blocks',
			array(
			  'api_url' => site_url( '/wp-json/' ),
			  'api_nonce' => wp_create_nonce( 'wp_rest' ),
			));
    }
    
    public static function render( $attributes ) {
		if ( ! isset( $attributes['groupId'] ) ) {
			return;
		}

		$group_id = $attributes['groupId'];

		if ( is_null( $group_id ) ) {
			return __( 'No group ID.', 'ccbpress-core' );
		}

		if ( has_filter( 'ccbpress_rest_api_admin_group' ) ) {
			$$data = apply_filters( 'ccbpress_rest_api_admin_group', null, $id );
		} else {
			$data = CCBPress()->ccb->get( array(
				'cache_lifespan'	=> CCBPress()->ccb->cache_lifespan( 'group_profile_from_id' ),
				'query_string'		=> array(
					'srv'					=> 'group_profile_from_id',
					'id'					=> $group_id,
					'include_image_link'	=> '1',
				),
			) );
		}

		if ( ! $data ) {
			return __( 'Group not found.', 'ccbpress-core' );
        }
        
        $show_group_image = true;
        if ( isset( $attributes['showGroupImage'] ) && false === $attributes['showGroupImage'] ) {
            $show_group_image = false;
        }

        $show_group_name = true;
        if ( isset( $attributes['showGroupName'] ) && false === $attributes['showGroupName'] ) {
            $show_group_name = false;
        }

        $show_group_desc = true;
        if ( isset( $attributes['showGroupDesc'] ) && false === $attributes['showGroupDesc'] ) {
            $show_group_desc = false;
        }

        $show_main_leader = true;
        if ( isset( $attributes['showMainLeader'] ) && false === $attributes['showMainLeader'] ) {
            $show_main_leader = false;
        }

        $show_main_leader_email = true;
        if ( isset( $attributes['showMainLeaderEmail'] ) && false === $attributes['showMainLeaderEmail'] ) {
            $show_main_leader_email = false;
        }

        $show_main_leader_phone = true;
        if ( isset( $attributes['showMainLeaderPhone'] ) && false === $attributes['showMainLeaderPhone'] ) {
            $show_main_leader_phone = false;
        }

        $show_registration_forms = true;
        if ( isset( $attributes['showRegistrationForms'] ) && false === $attributes['showRegistrationForms'] ) {
            $show_registration_forms = false;
        }

		ob_start();
		?>
		<div class="wp-block-ccbpress-group-info">
			Group Information
			<?php var_dump( $attributes ); ?>
		</div>
		<?php
		return ob_get_clean();
	}

}
CCBPress_Core_Group_Info_Block::init();