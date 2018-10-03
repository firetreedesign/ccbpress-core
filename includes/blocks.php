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

		require_once CCBPRESS_CORE_PLUGIN_DIR . 'src/group-info/index.php';
		require_once CCBPRESS_CORE_PLUGIN_DIR . 'src/login/index.php';
	}

	public static function block_init() {
		if ( function_exists( 'register_block_type' ) ) {
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

		// Scripts.
		wp_localize_script(
			'ccbpress-core-block-js',
			'ccbpress_core_blocks',
			array(
			  'api_url' => site_url( '/wp-json/' ),
			  'api_nonce' => wp_create_nonce( 'wp_rest' ),
			)
		);

		// Styles.
		wp_enqueue_style(
			'ccbpress-core-block-editor-css', // Handle.
			plugins_url( $editor_style_path, CCBPRESS_CORE_PLUGIN_FILE ), // Block editor CSS.
            array( 'wp-blocks' ), // Dependency to include the CSS after it.
            filemtime( plugin_dir_path( CCBPRESS_CORE_PLUGIN_FILE ) . $editor_style_path )
		);
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