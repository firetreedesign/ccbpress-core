<?php
/**
 * Login Block
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since 	1.3.0
 * @package CGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CCBPress Core Blocks class
 */
class CCBPress_Core_Login_Block {

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
    
    public static function block_init() {
		if ( ! function_exists( 'register_block_type' ) ) {
            return;
        }
            
        register_block_type( 'ccbpress/login', array(
            'render_callback' => __CLASS__ . '::render',
        ) );
    }

    public static function render( $attributes ) {
        $ccb_api_url = CCBPress()->ccb->api_url;
		$ccb_login_url = str_replace( 'api.php', 'login.php', $ccb_api_url );
        $ccb_password_url = str_replace( 'api.php', 'w_password.php', $ccb_api_url );
        $uniqid = uniqid();

		// Set the values passed from the widget/block options.
		$show_forgot_password = true;
        if ( isset( $attributes['showForgotPassword'] ) && false === $attributes['showForgotPassword'] ) {
			$show_forgot_password = false;
		}
	
		ob_start();
		?>
		<div class="wp-block-ccbpress-login">
			<form class="ccbpress-core-login" action="<?php echo esc_attr( $ccb_login_url ); ?>" method="post" target="_blank">
				<input type="hidden" name="ax" value="login" />
				<input type="hidden" name="rurl" value="" />
				<label for="username_<?php echo esc_attr( $uniqid ); ?>"><?php esc_html_e( 'Username:', 'ccbpress-core' ); ?></label>
				<input id="username_<?php echo esc_attr( $uniqid ); ?>" type="text" name="form[login]" value="" />
				<label for="password_<?php echo esc_attr( $uniqid ); ?>"><?php esc_html_e( 'Password:', 'ccbpress-core' ); ?></label>
				<input id="password_<?php echo esc_attr( $uniqid ); ?>" type="password" name="form[password]" value="" />
				<input type="submit" value="<?php esc_attr_e( 'Login', 'ccbpress-core' ); ?>" />
			</form>
			<?php if (true === $show_forgot_password ) : ?>
				<p>
					<a href="<?php echo esc_attr( $ccb_password_url ); ?>" target="_blank"><?php esc_html_e( 'Forgot username or password?', 'ccbpress-core' ); ?></a>
				</p>
			<?php endif; ?>
		</div>
		<?php
		return ob_get_clean();
    }

}
CCBPress_Core_Login_Block::init();