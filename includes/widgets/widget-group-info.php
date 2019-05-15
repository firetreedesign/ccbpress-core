<?php
/**
 * Widget - Group Info
 *
 * @since 1.0.0
 *
 * @package CCBPress Core
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'CCBPress_Widget_Group_Info' ) ) :

	/**
	 * Group Info Widget
	 *
	 * @since 1.0.0
	 */
	class CCBPress_Widget_Group_Info extends WP_Widget {

		/**
		 * Register the widget with WordPress
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			parent::__construct(
				'ccbpress_widget_group_info',
				__( 'Group Information (CCBPress)', 'ccbpress-core' ),
				array(
					'description' => __( 'Display group information from Church Community Builder.', 'ccbpress-core' ),
					'classname'   => 'ccbpress-group-info',
				)
			);

		}

		/**
		 * Front-end display of the widget
		 *
		 * @since 1.0.0
		 *
		 * @param  array $args     Widget arguments.
		 * @param  array $instance Saved values from database.
		 *
		 * @return void
		 */
		public function widget( $args, $instance ) {

			wp_enqueue_style( 'featherlight' );
			wp_enqueue_script( 'featherlight' );

			$group_id = '';
			if ( isset( $instance['group_id'] ) ) {
				$group_id = $instance['group_id'];
			}

			$show_group_name = 'show';
			if ( isset( $instance['show_group_name'] ) ) {
				$show_group_name = $instance['show_group_name'];
			}

			$show_group_description = 'show';
			if ( isset( $instance['show_group_description'] ) ) {
				$show_group_description = $instance['show_group_description'];
			}

			$show_group_image = 'show';
			if ( isset( $instance['show_group_image'] ) ) {
				$show_group_image = $instance['show_group_image'];
			}

			$show_group_leader_card = 'show';
			if ( isset( $instance['show_group_leader_card'] ) ) {
				$show_group_leader_card = $instance['show_group_leader_card'];
			}

			$show_group_leader_phone_numbers = 'show';
			if ( isset( $instance['show_group_leader_phone_numbers'] ) ) {
				$show_group_leader_phone_numbers = $instance['show_group_leader_phone_numbers'];
			}

			$show_group_leader_email = 'show';
			if ( isset( $instance['show_group_leader_email'] ) ) {
				$show_group_leader_email = $instance['show_group_leader_email'];
			}

			$show_group_registration_forms = 'show';
			if ( isset( $instance['show_group_registration_forms'] ) ) {
				$show_group_registration_forms = $instance['show_group_registration_forms'];
			}

			// Build the query to get the data from CCB.
			$ccbpress_data = false;
			if ( strlen( $group_id ) > 0 ) {
				$ccbpress_data = CCBPress()->ccb->get(
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

			echo wp_kses_post( $args['before_widget'] );

			if ( ! empty( $instance['title'] ) ) {
				echo wp_kses_post( $args['before_title'] ) . esc_html( apply_filters( 'widget_title', $instance['title'] ) ) . wp_kses_post( $args['after_title'] );
			}

			if ( false !== $ccbpress_data ) {

				// Define the array to hold all found group.
				$group = array();
				$group = $ccbpress_data->response->groups->group;

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
				$group->main_leader->image = $group_main_leader_profile->response->individuals->individual->image;

				// Set the values passed from the widget options.
				$group->widget_options->show_group_name                 = $show_group_name;
				$group->widget_options->show_group_image                = $show_group_image;
				$group->widget_options->show_group_description          = $show_group_description;
				$group->widget_options->show_group_leader               = $show_group_leader_card;
				$group->widget_options->show_group_leader_email         = $show_group_leader_email;
				$group->widget_options->show_group_leader_phone_numbers = $show_group_leader_phone_numbers;
				$group->widget_options->show_group_registration_forms   = $show_group_registration_forms;

				echo '<div class="wp-block-ccbpress-group-info">';
				// Echo the group data and apply any filters.
				echo $this->ccbpress_get_template( $group );
				echo '</div>';

			}

			echo wp_kses_post( $args['after_widget'] );

		}

		/**
		 * Back-end widget form
		 *
		 * @since 1.0.0
		 *
		 * @see WP_Widget::form()
		 *
		 * @param  array $instance Previously saved values from database.
		 *
		 * @return void
		 */
		public function form( $instance ) {

			wp_enqueue_script( 'chosen' );
			wp_enqueue_style( 'chosen' );

			$instance = wp_parse_args(
				$instance,
				array(
					'title'                           => '',
					'group_id'                        => '',
					'show_group_name'                 => 'true',
					'show_group_description'          => 'true',
					'show_group_image'                => 'true',
					'show_group_leader_card'          => 'true',
					'show_group_leader_phone_numbers' => 'false',
					'show_group_leader_email'         => 'false',
					'show_group_registration_forms'   => 'true',
				)
			);

			$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
			?>
			<div class="widget-content">

				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title: (optional)', 'ccbpress-core' ); ?></label>
					<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
				</p>

				<p class="ccbpress-select">
					<label for="<?php echo esc_attr( $this->get_field_id( 'group_id' ) ); ?>"><?php esc_html_e( 'Group Info From:', 'ccbpress-core' ); ?></label>
					<select name="<?php echo esc_attr( $this->get_field_name( 'group_id' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'group_id' ) ); ?>" class="widefat">
						<option value="none"><?php esc_html_e( 'None', 'ccbpress-core' ); ?></option>
						<?php
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

						if ( $ccb_groups ) {
							foreach ( $ccb_groups->response->groups->group as $group ) :
								?>
								<option value="<?php echo esc_attr( $group['id'] ); ?>" <?php selected( $group['id'], $instance['group_id'] ); ?>><?php echo esc_html( $group->name ); ?></option>
								<?php
							endforeach;
						}
						unset( $ccb_groups );
						?>
					</select>
				</p>
				<script>
				jQuery( document ).ready(function($) {
					if (typeof jQuery('#widgets-right .ccbpress-select select').chosen === "function") {
						jQuery('#widgets-right .ccbpress-select select').chosen({width: "100%", disable_search_threshold: 10});
					}
				});
				</script>
				<p>
					<strong><?php esc_html_e( 'What would you like to show?', 'ccbpress-core' ); ?></strong>
				</p>

				<table class="ccbpress_widget_table" style="width: 100%;">
					<tr>
						<td>
							<label for="<?php echo esc_attr( $this->get_field_id( 'show_group_image' ) ); ?>"><?php esc_html_e( 'Group Image', 'ccbpress-core' ); ?></label>
						</td>
						<td>
							<select id="<?php echo esc_attr( $this->get_field_id( 'show_group_image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_group_image' ) ); ?>">
								<option <?php selected( $instance['show_group_image'], 'show' ); ?> value="show"><?php esc_html_e( 'Show', 'ccbpress-core' ); ?></option>
								<option <?php selected( $instance['show_group_image'], 'hide' ); ?> value="hide"><?php esc_html_e( 'Hide', 'ccbpress-core' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<label for="<?php echo esc_attr( $this->get_field_id( 'show_group_name' ) ); ?>"><?php esc_html_e( 'Group Name', 'ccbpress-core' ); ?></label>
						</td>
						<td>
							<select id="<?php echo esc_attr( $this->get_field_id( 'show_group_name' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_group_name' ) ); ?>">
								<option <?php selected( $instance['show_group_name'], 'show' ); ?> value="show"><?php esc_html_e( 'Show', 'ccbpress-core' ); ?></option>
								<option <?php selected( $instance['show_group_name'], 'hide' ); ?> value="hide"><?php esc_html_e( 'Hide', 'ccbpress-core' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<label for="<?php echo esc_attr( $this->get_field_id( 'show_group_description' ) ); ?>"><?php esc_html_e( 'Group Description', 'ccbpress-core' ); ?></label>
						</td>
						<td>
							<select id="<?php echo esc_attr( $this->get_field_id( 'show_group_description' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_group_description' ) ); ?>">
								<option <?php selected( $instance['show_group_description'], 'show' ); ?> value="show"><?php esc_html_e( 'Show', 'ccbpress-core' ); ?></option>
								<option <?php selected( $instance['show_group_description'], 'hide' ); ?> value="hide"><?php esc_html_e( 'Hide', 'ccbpress-core' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<hr />
						</td>
					</tr>
					<tr>
						<td>
							<label for="<?php echo esc_attr( $this->get_field_id( 'show_group_leader_card' ) ); ?>"><?php esc_html_e( 'Main Leader', 'ccbpress-core' ); ?></label>
						</td>
						<td>
							<select id="<?php echo esc_attr( $this->get_field_id( 'show_group_leader_card' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_group_leader_card' ) ); ?>">
								<option <?php selected( $instance['show_group_leader_card'], 'show' ); ?> value="show"><?php esc_html_e( 'Show', 'ccbpress-core' ); ?></option>
								<option <?php selected( $instance['show_group_leader_card'], 'hide' ); ?> value="hide"><?php esc_html_e( 'Hide', 'ccbpress-core' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<label for="<?php echo esc_attr( $this->get_field_id( 'show_group_leader_email' ) ); ?>"><?php esc_html_e( 'Email Address', 'ccbpress-core' ); ?></label>
						</td>
						<td>
							<select id="<?php echo esc_attr( $this->get_field_id( 'show_group_leader_email' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_group_leader_email' ) ); ?>">
								<option <?php selected( $instance['show_group_leader_email'], 'show' ); ?> value="show"><?php esc_html_e( 'Show', 'ccbpress-core' ); ?></option>
								<option <?php selected( $instance['show_group_leader_email'], 'hide' ); ?> value="hide"><?php esc_html_e( 'Hide', 'ccbpress-core' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<label for="<?php echo esc_attr( $this->get_field_id( 'show_group_leader_phone_numbers' ) ); ?>"><?php esc_html_e( 'Phone Numbers', 'ccbpress-core' ); ?></label>
						</td>
						<td>
							<select id="<?php echo esc_attr( $this->get_field_id( 'show_group_leader_phone_numbers' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_group_leader_phone_numbers' ) ); ?>">
								<option <?php selected( $instance['show_group_leader_phone_numbers'], 'show' ); ?> value="show"><?php esc_html_e( 'Show', 'ccbpress-core' ); ?></option>
								<option <?php selected( $instance['show_group_leader_phone_numbers'], 'hide' ); ?> value="hide"><?php esc_html_e( 'Hide', 'ccbpress-core' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<hr />
						</td>
					</tr>
					<tr>
						<td>
							<label for="<?php echo esc_attr( $this->get_field_id( 'show_group_registration_forms' ) ); ?>"><?php esc_html_e( 'Registration Forms', 'ccbpress-core' ); ?></label>
						</td>
						<td>
							<select id="<?php echo esc_attr( $this->get_field_id( 'show_group_registration_forms' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_group_registration_forms' ) ); ?>">
								<option <?php selected( $instance['show_group_registration_forms'], 'show' ); ?> value="show"><?php esc_html_e( 'Show', 'ccbpress-core' ); ?></option>
								<option <?php selected( $instance['show_group_registration_forms'], 'hide' ); ?> value="hide"><?php esc_html_e( 'Hide', 'ccbpress-core' ); ?></option>
							</select>
						</td>
					</tr>
				</table>

			</div>
			<?php

		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @since 1.0.0
		 *
		 * @see WP_Widget::update()
		 *
		 * @param  array $new_instance Values sent to be saved.
		 * @param  array $old_instance Previously saved values from database.
		 *
		 * @return array               Updated safe values to be saved.
		 */
		public function update( $new_instance, $old_instance ) {

			$instance                                    = $old_instance;
			$instance['title']                           = ( ! empty( $new_instance['title'] ) ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
			$instance['group_id']                        = wp_strip_all_tags( stripslashes( $new_instance['group_id'] ) );
			$instance['show_group_name']                 = wp_strip_all_tags( stripslashes( $new_instance['show_group_name'] ) );
			$instance['show_group_description']          = wp_strip_all_tags( stripslashes( $new_instance['show_group_description'] ) );
			$instance['show_group_image']                = wp_strip_all_tags( stripslashes( $new_instance['show_group_image'] ) );
			$instance['show_group_leader_card']          = wp_strip_all_tags( stripslashes( $new_instance['show_group_leader_card'] ) );
			$instance['show_group_leader_phone_numbers'] = wp_strip_all_tags( stripslashes( $new_instance['show_group_leader_phone_numbers'] ) );
			$instance['show_group_leader_email']         = wp_strip_all_tags( stripslashes( $new_instance['show_group_leader_email'] ) );
			$instance['show_group_registration_forms']   = wp_strip_all_tags( stripslashes( $new_instance['show_group_registration_forms'] ) );

			return $instance;

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
		public function ccbpress_get_template( $group ) {

			ob_start();

			$template = new CCBPress_Widget_Group_Info_Template( 'group-info.php', CCBPRESS_CORE_PLUGIN_DIR );

			$template_path = $template->path();
			if ( false !== ( $template_path ) ) {
				include $template_path; // Include the template.
			} else {
				esc_html_e( 'Template not found. Please reinstall CCBPress Core.', 'ccbpress-core' );
			}

			// Return the output.
			return ob_get_clean();
		}
	}

endif;

/**
 * Register the Group Info widget
 *
 * @since 1.0.0
 *
 * @return void
 */
function register_ccbpress_widget_group_info() {

	if ( ! CCBPress()->ccb->is_connected() ) {
		return;
	}

	register_widget( 'CCBPress_Widget_Group_Info' );

}
add_action( 'widgets_init', 'register_ccbpress_widget_group_info' );

/**
 * Group Info Widget Template
 *
 * @since 1.0.0
 *
 * @extends CCBPress_Template
 *
 * @return void
 */
class CCBPress_Widget_Group_Info_Template extends CCBPress_Template {

	/**
	 * Show the group info widget's group name?
	 *
	 * @since 2.0.0
	 *
	 * @param object $group Group object.
	 *
	 * @return boolean
	 */
	public function show_group_name( $group ) {

		if ( 'hide' === (string) $group->widget_options->show_group_name ) {
			return false;
		}

		if ( strlen( $group->name ) === 0 ) {
			return false;
		}

		return true;

	}
	/**
	 * Show the group info widget's group image?
	 *
	 * @since 2.0.0
	 *
	 * @param object $group Group object.
	 *
	 * @return boolean
	 */
	public function show_group_image( $group ) {

		if ( 'hide' === (string) $group->widget_options->show_group_image ) {
			return false;
		}

		$upload_dir = wp_upload_dir();

		if ( false === strpos( $group->image, $upload_dir['baseurl'] ) ) {
			return false;
		}

		return true;

	}

	/**
	 * Show the group info widget's group description?
	 *
	 * @since 2.0.0
	 *
	 * @param object $group Group object.
	 *
	 * @return boolean
	 */
	public function show_group_desc( $group ) {

		if ( 'hide' === (string) $group->widget_options->show_group_description ) {
			return false;
		}

		if ( strlen( $group->description ) === 0 ) {
			return false;
		}

		return true;

	}

	/**
	 * Show the group info widget's group leader?
	 *
	 * @since 2.0.0
	 *
	 * @param object $group Group object.
	 *
	 * @return boolean
	 */
	public function show_group_leader( $group ) {

		if ( 'hide' === (string) $group->widget_options->show_group_leader ) {
			return false;
		}

		if ( strlen( (string) $group->main_leader['id'] ) === 0 ) {
			return false;
		}

		return true;

	}

	/**
	 * Show the group info widget's group leader email?
	 *
	 * @since 2.0.0
	 *
	 * @param object $group Group object.
	 *
	 * @return boolean
	 */
	public function show_group_leader_email( $group ) {

		if ( 'hide' === (string) $group->widget_options->show_group_leader_email ) {
			return false;
		}

		if ( strlen( $group->main_leader->email ) === 0 ) {
			return false;
		}

		return true;

	}

	/**
	 * Show the group info widget's group leader phone?
	 *
	 * @since 2.0.0
	 *
	 * @param object $group Group object.
	 *
	 * @return boolean
	 */
	public function show_group_leader_phone( $group ) {

		if ( 'hide' === (string) $group->widget_options->show_group_leader_phone_numbers ) {
			return false;
		}

		if ( ! is_object( $group->main_leader->phones ) ) {
			return false;
		}

		return true;

	}

	/**
	 * Show the group info widget's registration forms?
	 *
	 * @since 2.0.0
	 *
	 * @param object $group Group object.
	 *
	 * @return boolean
	 */
	public function show_registration_forms( $group ) {

		if ( 'hide' === (string) $group->widget_options->show_group_registration_forms ) {
			return false;
		}

		if ( ! isset( $group->registration_forms->form ) ) {
			return false;
		}

		$any_active = false;
		foreach ( $group->registration_forms->form as $registration_form ) {
			if ( $this->is_form_active( $registration_form ) ) {
				$any_active = true;
			}
		}

		if ( ! $any_active ) {
			return false;
		}

		return true;

	}

	/**
	 * Determines whether a form is active.
	 *
	 * @since 2.0.0
	 *
	 * @param object $registration_form Registration form object.
	 *
	 * @return boolean True/False.
	 */
	public function is_form_active( $registration_form ) {
		return CCBPress()->ccb->is_form_active( (string) $registration_form['id'] );

	}

	/**
	 * Build the CCB Easy Email URL
	 *
	 * @param array $args Arguments.
	 *
	 * @return string The URL.
	 */
	public function email_link( $args ) {

		$defaults = array(
			'individual_id' => null,
			'before'        => '',
			'after'         => '',
			'class'         => null,
			'target'        => '',
			'link_text'     => null,
		);

		$args = wp_parse_args( $args, $defaults );

		if ( is_null( $args['individual_id'] ) ) {
			return;
		}

		$individual_profile = CCBPress()->ccb->get(
			array(
				'service'        => 'individual_profile_from_id',
				'cache_lifespan' => CCBPress()->ccb->cache_lifespan( 'individual_profile_from_id' ),
				'query_string'   => array(
					'srv'              => 'individual_profile_from_id',
					'individual_id'    => $args['individual_id'],
					'include_inactive' => 0,
				),
			)
		);

		$full_name = $individual_profile->response->individuals->individual->full_name;

		if ( ! isset( $individual_profile->response->individuals->individual->email ) ) {
			return esc_html( $full_name );
		}

		$email = $individual_profile->response->individuals->individual->email;

		if ( strlen( $email ) === 0 ) {
			return esc_html( $full_name );
		}

		$class = '';
		if ( ! is_null( $args['class'] ) ) {
			$class = ' class="' . $args['class'] . '"';
		}

		$link_text = $full_name;
		if ( ! is_null( $args['link_text'] ) ) {
			$link_text = $args['link_text'];
		}

		return sprintf( '%s<a href="mailto:%s"%s target="%s">%s</a>%s', $args['before'], esc_attr( $email ), $class, $args['target'], esc_html( $link_text ), $args['after'] );

	}

	/**
	 * Return the lightbox class
	 *
	 * @since 1.0.0
	 * @deprecated 1.3.0
	 *
	 * @return string The lightbox class.
	 */
	public function lightbox_class() {

		$return_value = '';

		// Retrieve the lightbox settings from the plugin options.
		switch ( get_option( 'ccbpress_ccb_forms_lightbox' ) ) {

			case 'easy-fancybox':
				$return_value = 'fancybox-iframe';
				break;

			default:
				$return_value = '';
				break;

		}

		return $return_value;

	}

	/**
	 * Return the lightbox class
	 *
	 * @since 1.3.0
	 *
	 * @param string $class Additional class names.
	 *
	 * @return string The form link attributes.
	 */
	public function form_link_class( $class = '' ) {

		$class_array = array();

		if ( strlen( trim( $class ) ) > 0 ) {
			$class_array[] = trim( $class );
		}
		// Retrieve the lightbox settings from the plugin options.
		switch ( get_option( 'ccbpress_ccb_links_forms', 'lightbox' ) ) {

			case 'lightbox':
				$class_array[] = 'ccbpress-lightbox';
				break;

		}

		return implode( ' ', $class_array );
	}

	/**
	 * Detail styles
	 *
	 * @param object $group Group object.
	 * @return string
	 */
	public function detail_styles( $group ) {

		$style = '';

		if ( isset( $group->block_options->box_background_color ) ) {
			$style .= 'background-color: ' . $group->block_options->box_background_color . ';';
		}

		if ( isset( $group->block_options->box_border_color ) ) {
			$style .= 'border-color: ' . $group->block_options->box_border_color . ';';
		}

		return $style;

	}

}
