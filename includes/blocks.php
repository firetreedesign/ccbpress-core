<?php
/**
 * Blocks Initializer
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
class CCBPress_Core_Blocks {
	
	/**
	 * Initialize the class
	 *
	 * @since 1.2.0
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'enqueue_block_assets', 'CCBPress_Core_Blocks::enqueue_block_assets' );
		add_action( 'enqueue_block_editor_assets', 'CCBPress_Core_Blocks::enqueue_block_editor_assets' );
		add_action( 'init', 'CCBPress_Core_Blocks::block_init' );
	}

	public static function block_init() {
		if ( function_exists( 'register_block_type' ) ) {
            
            register_block_type( 'ccbpress/login', array(
				'render_callback' => 'CCBPress_Core_Blocks::render_login',
            ) );

            register_block_type( 'ccbpress/online-giving', array(
				'render_callback' => 'CCBPress_Core_Blocks::render_online_giving',
			) );
		}
	}

	

	/**
	 * Enqueue Gutenberg block assets for both frontend + backend.
	 *
	 * @since 1.2.0
	 *
	 * @return void
	 */
	public static function enqueue_block_assets() {
        
        // $block_path = '/dist/blocks.build.js';
        $style_path = '/dist/blocks.style.build.css';

		// Styles.
		wp_enqueue_style(
			'ccbpress-core-blocks-css', // Handle.
			plugins_url( $style_path, CCBPRESS_CORE_PLUGIN_FILE ), // Block style CSS.
            array( 'wp-blocks' ), // Dependency to include the CSS after it.
            filemtime( plugin_dir_path( CCBPRESS_CORE_PLUGIN_FILE ) . $style_path )
		);
	}

	/**
	 * Enqueue Gutenberg block assets for both frontend + backend.
	 *
	 * @since 1.2.0
	 *
	 * @return void
	 */
	public static function enqueue_block_editor_assets() {
        
        $editor_block_path = '/dist/blocks.build.js';
        $editor_style_path = '/dist/blocks.style.build.css';

		// Scripts.
		wp_enqueue_script(
			'ccbpress-core-block-js', // Handle.
			plugins_url( $editor_block_path, CCBPRESS_CORE_PLUGIN_FILE ), // Block.build.js: We register the block here. Built with Webpack.
            array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components' ), // Dependencies, defined above.
            filemtime( plugin_dir_path( CCBPRESS_CORE_PLUGIN_FILE ) . $editor_block_path )
		);

		// Styles.
		wp_enqueue_style(
			'ccbpress-core-block-editor-css', // Handle.
			plugins_url( $editor_style_path, CCBPRESS_CORE_PLUGIN_FILE ), // Block editor CSS.
            array( 'wp-blocks' ), // Dependency to include the CSS after it.
            filemtime( plugin_dir_path( CCBPRESS_CORE_PLUGIN_FILE ) . $editor_style_path )
		);
	}

	public static function render_login( $attributes ) {
		$ccb_api_url = CCBPress()->ccb->api_url;
		$ccb_login_url = str_replace( 'api.php', 'login.php', $ccb_api_url );
		$ccb_password_url = str_replace( 'api.php', 'w_password.php', $ccb_api_url );
		$uniqid = uniqid();
	
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
			<p>
				<a href="<?php echo esc_attr( $ccb_password_url ); ?>" target="_blank"><?php esc_html_e( 'Forgot username or password?', 'ccbpress-core' ); ?></a>
			</p>
		</div>
		<?php
		return ob_get_clean();
    }
    
    public static function render_online_giving( $attributes ) {
		$ccb_api_url = CCBPress()->ccb->api_url;
		$ccb_online_giving_url = str_replace( 'api.php', 'w_give_online.php', $ccb_api_url );

		ob_start();
		?>
		<div class="wp-block-ccbpress-online-giving">
			<form action="<?php esc_attr_e( $ccb_online_giving_url ); ?>" target="_blank">
			    <input type="submit" value="<?php esc_attr_e( __('Give Now', 'ccbpress-core') ); ?>">
			</form>
		</div>
		<?php
		return ob_get_clean();
	}
}
CCBPress_Core_Blocks::init();