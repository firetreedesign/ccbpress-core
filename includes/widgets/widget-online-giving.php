<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'CCBPress_Widget_Online_Giving' ) ) :

	class CCBPress_Widget_Online_Giving extends WP_Widget {

		/**
		 * Register the widget with WordPress
		 *
		 * @since 1.0.0
		 */
		function __construct() {

			parent::__construct(
				'ccbpress_widget_online_giving',
				__('Online Giving (CCBPress)', 'ccbpress-core'),
				array( 'description' => __('Displays a link to CCB Online Giving.', 'ccbpress-core' ), )
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

			$ccb_api_url = CCBPress()->ccb->api_url;
			$ccb_online_giving_url = str_replace( 'api.php', 'w_give_online.php', $ccb_api_url );

			echo $args['before_widget'];

			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
			}
			ob_start();
			?>
			<form action="<?php esc_attr_e( $ccb_online_giving_url ); ?>" target="_blank">
			    <input type="submit" value="<?php esc_attr_e( __('Give Now', 'ccbpress-core') ); ?>">
			</form>
			<?php
			echo ob_get_clean();
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

			$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
			?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'ccbpress-core'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<p>
				<?php _e('Displays a link to Church Community Builder Online Giving.', 'ccbpress-core' ); ?>
			</p>
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

			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			return $instance;

		}

	}

	// register CCBPress_Widget_Online_Giving widget.
	function register_ccbpress_widget_online_giving() {

		if ( ! CCBPress()->ccb->is_connected() ) {
			return;
		}

		register_widget( 'CCBPress_Widget_Online_Giving' );

	}
	add_action( 'widgets_init', 'register_ccbpress_widget_online_giving' );

endif;
