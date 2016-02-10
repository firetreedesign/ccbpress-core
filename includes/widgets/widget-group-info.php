<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'CCBPress_Widget_Group_Info' ) ) :

class CCBPress_Widget_Group_Info extends WP_Widget {

	/**
	 * Register the widget with WordPress
	 *
	 * @since 1.0.0
	 */
	function __construct() {

		parent::__construct(
			'ccbpress_widget_group_info',
			__('Group Information (CCBPress)', 'ccbpress-core'),
			array(
				'description'	=> __('Display group information from Church Community Builder.', 'ccbpress-core' ),
				'classname'		=> 'ccbpress-group-info'
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

		$group_id 							= $instance['group_id'];
		$show_group_name 					= $instance['show_group_name'];
		$show_group_description 			= $instance['show_group_description'];
		$show_group_image 					= $instance['show_group_image'];
		$show_group_leader_card 			= $instance['show_group_leader_card'];
		$show_group_leader_phone_numbers	= $instance['show_group_leader_phone_numbers'];
		$show_group_leader_email			= $instance['show_group_leader_email'];
		$show_group_registration_forms 		= $instance['show_group_registration_forms'];

		// Build the query to get the data from CCB
		$ccbpress_args = array(
			'id' => $group_id,
		);
		$ccbpress_data = false;
		if ( strlen( $group_id ) > 0 ) {
			$ccbpress_data = CCBPress()->ccb->group_profile_from_id( $ccbpress_args );
		}

		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		if ( ! $ccbpress_data === false ) {

			// Define the array to hold all found group
			$group = array();
			$group = $ccbpress_data->response->groups->group;
			//$group_id = (string)$group['id'];

			// Get the cached group image
			$group->image = CCBPress()->ccb->get_image( $group_id, 'group' );

			// Get their profile image from their user profile
			$group_main_leader_profile = CCBPress()->ccb->individual_profile_from_id( array( 'individual_id' => (string)$group->main_leader['id'] ) );
			$group->main_leader->image	= $group_main_leader_profile->response->individuals->individual->image;

			// Set the values passed from the widget options
			$group->widget_options->show_group_name					= $show_group_name;
			$group->widget_options->show_group_image				= $show_group_image;
			$group->widget_options->show_group_description			= $show_group_description;
			$group->widget_options->show_group_leader				= $show_group_leader_card;
			$group->widget_options->show_group_leader_email			= $show_group_leader_email;
			$group->widget_options->show_group_leader_phone_numbers	= $show_group_leader_phone_numbers;
			$group->widget_options->show_group_registration_forms	= $show_group_registration_forms;

			// Echo the group data and apply any filters
			echo $this->ccbpress_get_template( $group );

		}

		echo $args['after_widget'];

	}

	/**
	 * Back-end widget form
	 *
	 * @since 1.0.0
	 *
	 * @see WP_Widget::form()
	 *
	 * @param  array $instance Previously saved values from database
	 *
	 * @return void
	 */
	public function form( $instance ) {

		wp_enqueue_script( 'ccbpress-select2' );
		wp_enqueue_style( 'ccbpress-select2' );

		$instance = wp_parse_args( $instance, array(
			'title'								=> '',
			'group_id'							=> '',
			'show_group_name'					=> 'true',
			'show_group_description'			=> 'true',
			'show_group_image'					=> 'true',
			'show_group_leader_card'			=> 'true',
			'show_group_leader_phone_numbers'	=> 'false',
			'show_group_leader_email'			=> 'false',
			'show_group_registration_forms'		=> 'true'
		) );

		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		?>
		<div class="widget-content">

			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title: (optional)', 'ccbpress-core' ); ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'group_id' ); ?>"><?php _e( 'Group Info From:', 'ccbpress-core' ); ?></label>
				<select name="<?php echo $this->get_field_name( 'group_id' ); ?>" id="<?php echo $this->get_field_id( 'group_id' ); ?>" data-placeholder="Start typing to search..." class="widefat">
					<option value="none"><?php _e('None', 'ccbpress-core'); ?></option>
					<?php
					$ccb_groups = CCBPress()->ccb->group_profiles( array( 'cache_lifespan' => 2880 ) );

					if ( $ccb_groups ) {
						foreach( $ccb_groups->response->groups->group as $group ) : ?>
							<option value="<?php esc_attr_e( $group['id'] ); ?>" <?php selected( in_array( $group['id'], explode( ",", $instance['group_id'] ) ), true ); ?>><?php echo $group->name; ?></option>
						<?php endforeach;
					}
					unset( $ccb_groups );
					?>
				</select>
				<script>
				jQuery( document ).ready(function($) {
					jQuery('#<?php echo $this->get_field_id( 'group_id' ); ?>').select2ccbpress({ width: '100%' });

					jQuery(document).on('widget-updated widget-added', function() {
						jQuery('#<?php echo $this->get_field_id( 'group_id' ); ?>').select2ccbpress({ width: '100%' });
					} );
				} );
				</script>
				<style>
				.select2-dropdown {
					z-index: 510000 !important;
				}
				</style>
			</p>

			<p>
				<strong><?php _e( 'What would you like to show?', 'ccbpress-core' ); ?></strong>
			</p>

			<table class="ccbpress_widget_table">
				<tr>
					<td>
						<label for="<?php echo $this->get_field_id( 'show_group_name' ); ?>"><?php _e( 'Group Name', 'ccbpress-core' ); ?></label>
					</td>
					<td>
						<select id="<?php echo $this->get_field_id( 'show_group_name' ); ?>" name="<?php echo $this->get_field_name( 'show_group_name' );  ?>">
							<option <?php selected( $instance['show_group_name'], 'show' ); ?> value="show"><?php _e( 'Show', 'ccbpress-core' ); ?></option>
							<option <?php selected( $instance['show_group_name'], 'hide' ); ?> value="hide"><?php _e( 'Hide', 'ccbpress-core' ); ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<label for="<?php echo $this->get_field_id( 'show_group_image' ); ?>"><?php _e( 'Group Image', 'ccbpress-core' ); ?></label>
					</td>
					<td>
						<select id="<?php echo $this->get_field_id( 'show_group_image' ); ?>" name="<?php echo $this->get_field_name( 'show_group_image' );  ?>">
							<option <?php selected( $instance['show_group_image'], 'show' ); ?> value="show"><?php _e( 'Show', 'ccbpress-core' ); ?></option>
							<option <?php selected( $instance['show_group_image'], 'hide' ); ?> value="hide"><?php _e( 'Hide', 'ccbpress-core' ); ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<label for="<?php echo $this->get_field_id( 'show_group_description' ); ?>"><?php _e( 'Group Description', 'ccbpress-core' ); ?></label>
					</td>
					<td>
						<select id="<?php echo $this->get_field_id( 'show_group_description' ); ?>" name="<?php echo $this->get_field_name( 'show_group_description' );  ?>">
							<option <?php selected( $instance['show_group_description'], 'show' ); ?> value="show"><?php _e( 'Show', 'ccbpress-core' ); ?></option>
							<option <?php selected( $instance['show_group_description'], 'hide' ); ?> value="hide"><?php _e( 'Hide', 'ccbpress-core' ); ?></option>
						</select>
					</td>
				</tr>
			</table>

			<p>
				<div class="ccbpress_widget_group">
					<div class="ccbpress_widget_group_title">
						<select id="<?php echo $this->get_field_id( 'show_group_leader_card' ); ?>" name="<?php echo $this->get_field_name( 'show_group_leader_card' );  ?>" style="float: right;">
							<option <?php selected( $instance['show_group_leader_card'], 'show' ); ?> value="show"><?php _e( 'Show', 'ccbpress-core' ); ?></option>
							<option <?php selected( $instance['show_group_leader_card'], 'hide' ); ?> value="hide"><?php _e( 'Hide', 'ccbpress-core' ); ?></option>
						</select>
						<label for="<?php echo $this->get_field_id( 'show_group_leader_card' ); ?>"><?php _e( 'Main Leader', 'ccbpress-core' ); ?></label>
					</div>
					<div class="ccbpress_widget_group_inside">
						<table class="ccbpress_widget_table">
							<tr>
								<td>
									<label for="<?php echo $this->get_field_id( 'show_group_leader_email' ); ?>"><?php _e( 'Email Address', 'ccbpress-core' ); ?></label>
								</td>
								<td>
									<select id="<?php echo $this->get_field_id( 'show_group_leader_email' ); ?>" name="<?php echo $this->get_field_name( 'show_group_leader_email' );  ?>">
										<option <?php selected( $instance['show_group_leader_email'], 'show' ); ?> value="show"><?php _e( 'Show', 'ccbpress-core' ); ?></option>
										<option <?php selected( $instance['show_group_leader_email'], 'hide' ); ?> value="hide"><?php _e( 'Hide', 'ccbpress-core' ); ?></option>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									<label for="<?php echo $this->get_field_id( 'show_group_leader_phone_numbers' ); ?>"><?php _e( 'Phone Numbers', 'ccbpress-core' ); ?></label>
								</td>
								<td>
									<select id="<?php echo $this->get_field_id( 'show_group_leader_phone_numbers' ); ?>" name="<?php echo $this->get_field_name( 'show_group_leader_phone_numbers' );  ?>">
										<option <?php selected( $instance['show_group_leader_phone_numbers'], 'show' ); ?> value="show"><?php _e( 'Show', 'ccbpress-core' ); ?></option>
										<option <?php selected( $instance['show_group_leader_phone_numbers'], 'hide' ); ?> value="hide"><?php _e( 'Hide', 'ccbpress-core' ); ?></option>
									</select>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</p>

			<p>
			<table class="ccbpress_widget_table">
				<tr>
					<td>
						<label for="<?php echo $this->get_field_id( 'show_group_registration_forms' ); ?>"><?php _e( 'Registration Forms', 'ccbpress-core' ); ?></label>
					</td>
					<td>
						<select id="<?php echo $this->get_field_id( 'show_group_registration_forms' ); ?>" name="<?php echo $this->get_field_name( 'show_group_registration_forms' ); ?>">
							<option <?php selected( $instance['show_group_registration_forms'], 'show' ); ?> value="show"><?php _e( 'Show', 'ccbpress-core' ); ?></option>
							<option <?php selected( $instance['show_group_registration_forms'], 'hide' ); ?> value="hide"><?php _e( 'Hide', 'ccbpress-core' ); ?></option>
						</select>
					</td>
				</tr>
			</table>
			</p>

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

		$instance = $old_instance;
		$instance['title']								= ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['group_id']							= strip_tags( stripslashes( $new_instance['group_id'] ) );
		$instance['show_group_name']					= strip_tags( stripslashes( $new_instance['show_group_name'] ) );
		$instance['show_group_description']				= strip_tags( stripslashes( $new_instance['show_group_description'] ) );
		$instance['show_group_image']					= strip_tags( stripslashes( $new_instance['show_group_image'] ) );
		$instance['show_group_leader_card']				= strip_tags( stripslashes( $new_instance['show_group_leader_card'] ) );
		$instance['show_group_leader_phone_numbers']	= strip_tags( stripslashes( $new_instance['show_group_leader_phone_numbers'] ) );
		$instance['show_group_leader_email']			= strip_tags( stripslashes( $new_instance['show_group_leader_email'] ) );
		$instance['show_group_registration_forms']		= strip_tags( stripslashes( $new_instance['show_group_registration_forms'] ) );

		return $instance;

	}

	public function ccbpress_get_template( $group ) {

		ob_start();

		$template = new CCBPress_Widget_Group_Info_Template( 'group-info.php', CCBPRESS_CORE_PLUGIN_DIR );

		if ( ! false == ( $template_path = $template->path() ) ) {
			include( $template_path ); // Include the template
		} else {
			_e('Template not found. Please reinstall CCBPress.', 'ccbpress-core');
		}

		// Return the output
		return ob_get_clean();

	}

}

// register CCBPress_Widget_Group_Info widget
function register_ccbpress_widget_group_info() {

    $ccbpress_ccb = get_option( 'ccbpress_ccb' );
	if ( isset( $ccbpress_ccb['connection_test'] ) && $ccbpress_ccb['connection_test'] === 'success' ) {
    	register_widget( 'CCBPress_Widget_Group_Info' );
    }

}
add_action( 'widgets_init', 'register_ccbpress_widget_group_info' );

endif;

class CCBPress_Widget_Group_Info_Template extends CCBPress_Template {

	/**
	 * Show the group info widget's group name?
	 *
	 * @since 2.0.0
	 *
	 * @return	boolean
	 */
	public function show_group_name( $group ) {

		$group_name = $group->name;

		if ( $group->widget_options->show_group_name == 'show' && strlen( $group_name ) > 0 ) {
			return true;
		}

		return false;

	}
	/**
	 * Show the group info widget's group image?
	 *
	 * @since 2.0.0
	 *
	 * @return	boolean
	 */
	public function show_group_image( $group ) {

		$group_image = $group->image;

		if ( $group->widget_options->show_group_image == 'show' && strlen( $group_image ) > 0 ) {
			return true;
		}

		return false;

	}

	/**
	 * Show the group info widget's group description?
	 *
	 * @since 2.0.0
	 *
	 * @return	boolean
	 */
	public function show_group_desc( $group ) {

		$group_description = $group->description;

		if ( $group->widget_options->show_group_description == 'show' && strlen( $group_description ) > 0 ) {
			return true;
		}

		return false;

	}

	/**
	 * Show the group info widget's group leader?
	 *
	 * @since 2.0.0
	 *
	 * @return	boolean
	 */
	public function show_group_leader( $group ) {

		$group_main_leader_id = (string)$group->main_leader['id'];

		if ( $group->widget_options->show_group_leader == 'show' && strlen( $group_main_leader_id ) > 0 ) {
			return true;
		}

		return false;

	}

	/**
	 * Show the group info widget's group leader email?
	 *
	 * @since 2.0.0
	 *
	 * @return	boolean
	 */
	public function show_group_leader_email( $group ) {

		$group_main_leader_email = $group->main_leader->email;

		if ( $group->widget_options->show_group_leader_email == 'show' && strlen( $group_main_leader_email ) > 0 ) {
			return true;
		}

		return false;

	}

	/**
	 * Show the group info widget's group leader phone?
	 *
	 * @since 2.0.0
	 *
	 * @return	boolean
	 */
	public function show_group_leader_phone( $group ) {

		$group_main_leader_phones = $group->main_leader->phones;

		if ( $group->widget_options->show_group_leader_phone_numbers == 'show' && is_object( $group_main_leader_phones ) ) {
			return true;
		}

		return false;

	}

	/**
	 * Show the group info widget's registration forms?
	 *
	 * @since 2.0.0
	 *
	 * @return	boolean
	 */
	public function show_registration_forms( $group ) {

		if ( $group->widget_options->show_group_registration_forms == 'show' ) {
			return true;
		}

		return false;

	}

	/**
	 * Determines whether a form is active.
	 *
	 * @since 2.0.0
	 *
	 * @return boolean	True/False.
	 */
	public function is_form_active( $registration_form ) {

		return CCBPress()->ccb->is_form_active( (string)$registration_form['id'] );

	}

	/**
	 * Build the CCB Easy Email URL
	 *
	 * @param	string	$individual_id			The individual id.
	 * @param	string	$group_id				The group id.
	 * @param	string	$individual_full_name	The individual's full name.
	 *
	 * @return	string	The URL.
	 */
	public function email_url( $individual_id, $group_id, $individual_full_name ) {

		$url = str_replace( 'api.php', 'easy_email.php', CCBPress()->ccb->api_url );
		$url = add_query_arg( 'ax', 'create_new', $url );
		$url = add_query_arg( 'individual_id', $individual_id, $url );
		$url = add_query_arg( 'group_id', $group_id, $url );
		$url = add_query_arg( 'individual_full_name', $individual_full_name, $url );

		return $url;

	}

	/**
	 * Return the lightbox class
	 *
	 * @since 2.0.0
	 *
	 * @return string	The lightbox class.
	 */
	public function lightbox_class() {

		// Retrieve the lightbox settings from the plugin options
		switch ( get_option( 'ccbpress_ccb_forms_lightbox' ) ) {

			case 'easy-fancybox':
				return 'fancybox-iframe';
				break;

			default:
				return '';
				break;

		}

	}

}
