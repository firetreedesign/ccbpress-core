<?php
/**
 * Group Info Block
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since 1.0.0
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
		add_action( 'init', __CLASS__ . '::block_init' );
	}

	/**
	 * Block Init
	 *
	 * @return void
	 */
	public static function block_init() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		register_block_type(
			'ccbpress/group-info',
			array(
				'render_callback' => __CLASS__ . '::render',
			)
		);
	}

	/**
	 * Render
	 *
	 * @param array $attributes Attributes.
	 * @return string
	 */
	public static function render( $attributes ) {
		if ( ! isset( $attributes['groupId'] ) ) {
			return;
		}

		wp_enqueue_style( 'featherlight' );
		wp_enqueue_script( 'featherlight' );

		$group_id = $attributes['groupId'];

		if ( is_null( $group_id ) ) {
			return __( 'No group ID.', 'ccbpress-core' );
		}

		if ( has_filter( 'ccbpress_rest_api_admin_group' ) ) {
			$data = apply_filters( 'ccbpress_rest_api_admin_group', null, $id );
		} else {
			$data = CCBPress()->ccb->get(
				array(
					'cache_lifespan' => CCBPress()->ccb->cache_lifespan( 'group_profile_from_id' ),
					'query_string'   => array(
						'srv'                => 'group_profile_from_id',
						'id'                 => $group_id,
						'include_image_link' => '1',
					),
				)
			);
		}

		if ( ! $data ) {
			return __( 'Group not found.', 'ccbpress-core' );
		}

		// Define the array to hold all found group.
		$group = array();
		$group = $data->response->groups->group;

		// Get the cached group image.
		$group->image = CCBPress()->ccb->get_image( $group_id, 'group' );

		// Get their profile image from their user profile.
		$group_main_leader_profile = CCBPress()->ccb->get(
			array(
				'cache_lifespan' => CCBPress()->ccb->cache_lifespan( 'individual_profile_from_id' ),
				'query_string'   => array(
					'srv'              => 'individual_profile_from_id',
					'individual_id'    => (string) $group->main_leader['id'],
					'include_inactive' => 0,
				),
			)
		);
		$group->main_leader->image = CCBPress()->ccb->get_image( $group->main_leader['id'], 'individual' );

		// Set the values passed from the widget/block options.
		$show_group_image = 'show';
		if ( isset( $attributes['showGroupImage'] ) && false === $attributes['showGroupImage'] ) {
			$show_group_image = 'hide';
		}
		$group->widget_options->show_group_image = $show_group_image;

		$show_group_name = 'show';
		if ( isset( $attributes['showGroupName'] ) && false === $attributes['showGroupName'] ) {
			$show_group_name = 'hide';
		}
		$group->widget_options->show_group_name = $show_group_name;

		$show_group_description = 'show';
		if ( isset( $attributes['showGroupDesc'] ) && false === $attributes['showGroupDesc'] ) {
			$show_group_description = 'hide';
		}
		$group->widget_options->show_group_description = $show_group_description;

		$show_main_leader = 'show';
		if ( isset( $attributes['showMainLeader'] ) && false === $attributes['showMainLeader'] ) {
			$show_main_leader = 'hide';
		}
		$group->widget_options->show_group_leader = $show_main_leader;

		$show_main_leader_email = 'show';
		if ( isset( $attributes['showMainLeaderEmail'] ) && false === $attributes['showMainLeaderEmail'] ) {
			$show_main_leader_email = 'hide';
		}
		$group->widget_options->show_group_leader_email = $show_main_leader_email;

		$show_main_leader_phone = 'show';
		if ( isset( $attributes['showMainLeaderPhone'] ) && false === $attributes['showMainLeaderPhone'] ) {
			$show_main_leader_phone = 'hide';
		}
		$group->widget_options->show_group_leader_phone_numbers = $show_main_leader_phone;

		$show_registration_forms = 'show';
		if ( isset( $attributes['showRegistrationForms'] ) && false === $attributes['showRegistrationForms'] ) {
			$show_registration_forms = 'hide';
		}
		$group->widget_options->show_group_registration_forms = $show_registration_forms;

		if ( isset( $attributes['boxBackgroundColor'] ) ) {
			$group->block_options->box_background_color = $attributes['boxBackgroundColor'];
		}

		if ( isset( $attributes['boxBorderColor'] ) ) {
			$group->block_options->box_border_color = $attributes['boxBorderColor'];
		}

		ob_start();
		echo '<div class="wp-block-ccbpress-group-info">';
		// Echo the group data and apply any filters.
		echo self::get_template( $group );
		echo '</div>';
		return ob_get_clean();
	}

	/**
	 * Get the template
	 *
	 * @since 1.0.0
	 *
	 * @param  object $group Group object.
	 *
	 * @return string
	 */
	private static function get_template( $group ) {

		ob_start();

		$template = new CCBPress_Widget_Group_Info_Template( 'group-info.php', CCBPRESS_CORE_PLUGIN_DIR );

		$template_path = $template->path();
		if ( false !== $template_path ) {
			include $template_path; // Include the template.
		} else {
			esc_html_e( 'Template not found. Please reinstall CCBPress Core.', 'ccbpress-core' );
		}

		// Return the output.
		return ob_get_clean();
	}

}
CCBPress_Core_Group_Info_Block::init();
