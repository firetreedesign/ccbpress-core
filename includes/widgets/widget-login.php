<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'CCBPress_Widget_Login' ) ) :

	class CCBPress_Widget_Login extends WP_Widget {

		/**
		 * Register the widget with WordPress
		 *
		 * @since 1.0.0
		 */
		function __construct() {

			parent::__construct(
				'ccbpress_widget_login',
				__( 'CCB Login (CCBPress)', 'ccbpress-core' ),
				array( 'description' => __('Displays a login form for CCB.', 'ccbpress-core' ), )
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
			$ccb_login_url = str_replace( 'api.php', 'login.php', $ccb_api_url );
			$ccb_password_url = str_replace( 'api.php', 'w_password.php', $ccb_api_url );

			echo $args['before_widget'];

			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
			}
			ob_start();
			?>
			<form class="ccbpress-core-login" action="<?php echo esc_attr( $ccb_login_url ); ?>" method="post" target="_blank">
				<input type="hidden" name="ax" value="login" />
				<input type="hidden" name="rurl" value="" />
				<label for="username_<?php echo esc_attr( $args['widget_id'] ); ?>"><?php esc_html_e( 'Username:', 'ccbpress-core' ); ?></label>
				<input id="username_<?php echo esc_attr( $args['widget_id'] ); ?>" type="text" name="form[login]" value="" />
				<label for="password_<?php echo esc_attr( $args['widget_id'] ); ?>"><?php esc_html_e( 'Password:', 'ccbpress-core' ); ?></label>
				<input id="password_<?php echo esc_attr( $args['widget_id'] ); ?>" type="password" name="form[password]" value="" />
				<input type="submit" value="<?php esc_attr_e( 'Login', 'ccbpress-core' ); ?>" />
			</form>
			<p>
				<a href="<?php echo esc_attr( $ccb_password_url ); ?>" target="_blank"><?php esc_html_e( 'Forgot your password?', 'ccbpress-core' ); ?></a>
			</p>
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
				<?php _e('Displays a login form for Church Community Builder.', 'ccbpress-core' ); ?>
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

	function register_ccbpress_widget_login() {

		if ( ! CCBPress()->ccb->is_connected() ) {
			return;
		}

		register_widget( 'CCBPress_Widget_Login' );

	}
	add_action( 'widgets_init', 'register_ccbpress_widget_login' );

endif;
