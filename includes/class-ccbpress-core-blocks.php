<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since 1.0.0
 * @package CCBPress
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

		if ( version_compare( $GLOBALS['wp_version'], '5.8alpha', '>=' ) ) {
			add_filter( 'block_categories_all', __CLASS__ . '::block_categories_all', 10, 2 );
		} else {
			add_filter( 'block_categories', __CLASS__ . '::block_categories', 10, 2 );
		}

		require_once CCBPRESS_CORE_PLUGIN_DIR . 'src/group-info/index.php';
		require_once CCBPRESS_CORE_PLUGIN_DIR . 'src/login/index.php';
		require_once CCBPRESS_CORE_PLUGIN_DIR . 'src/online-giving/index.php';
	}

	/**
	 * Enqueue Gutenberg block assets for both frontend + backend.
	 *
	 * @since 1.2.0
	 * @return void
	 */
	public static function enqueue_block_assets() {
		$style_path = '/dist/blocks.style.build.css';

		// Styles.
		wp_enqueue_style(
			'ccbpress-core-blocks-css', // Handle.
			plugins_url( $style_path, CCBPRESS_CORE_PLUGIN_FILE ), // Block style CSS.
			array(), // Dependency to include the CSS after it.
			filemtime( plugin_dir_path( CCBPRESS_CORE_PLUGIN_FILE ) . $style_path )
		);
	}

	/**
	 * Enqueue Gutenberg block assets for both frontend + backend.
	 *
	 * @since 1.2.0
	 * @return void
	 */
	public static function enqueue_block_editor_assets() {

		$editor_block_path = '/dist/blocks.build.js';
		$editor_style_path = '/dist/blocks.style.build.css';

		// Scripts.
		wp_enqueue_script(
			'ccbpress-core-block-js', // Handle.
			plugins_url( $editor_block_path, CCBPRESS_CORE_PLUGIN_FILE ), // Block.build.js: We register the block here. Built with Webpack.
			array( 'wp-i18n', 'wp-element', 'wp-components', 'wp-blocks', 'wp-block-editor' ), // Dependencies, defined above.
			filemtime( plugin_dir_path( CCBPRESS_CORE_PLUGIN_FILE ) . $editor_block_path ),
			true
		);

		// Scripts.
		wp_localize_script(
			'ccbpress-core-block-js',
			'ccbpress_core_blocks',
			array(
				'api_url'   => site_url( '/wp-json/' ),
				'api_nonce' => wp_create_nonce( 'wp_rest' ),
			)
		);

		// Styles.
		wp_enqueue_style(
			'ccbpress-core-block-editor-css', // Handle.
			plugins_url( $editor_style_path, CCBPRESS_CORE_PLUGIN_FILE ), // Block editor CSS.
			array(), // Dependency to include the CSS after it.
			filemtime( plugin_dir_path( CCBPRESS_CORE_PLUGIN_FILE ) . $editor_style_path )
		);
	}

	/**
	 * Register a new block category for Church Data Connect.
	 *
	 * @since 1.3.0
	 * @deprecated 1.4.3 As of WordPress 5.8
	 * @see CCBPress_Core_Blocks::block_categories_all()
	 * @param array  $categories Block categories.
	 * @param object $post Post object.
	 * @return array
	 */
	public static function block_categories( $categories, $post ) {
		return array_merge(
			$categories,
			array(
				array(
					'slug'  => 'ccbpress',
					'title' => __( 'Church Data Connect for Church Community Builder', 'ccbpress-core' ),
					'icon'  => null,
				),
			)
		);
	}

	/**
	 * Register a new block category for Church Data Connect.
	 *
	 * @since 1.4.3
	 * @param array  $block_categories Block categories.
	 * @param object $editor_context Editor context.
	 * @return array
	 */
	public static function block_categories_all( $block_categories, $editor_context ) {
		array_push(
			$block_categories,
			array(
				'slug'  => 'ccbpress',
				'title' => __( 'Church Data Connect for Church Community Builder', 'ccbpress-core' ),
				'icon'  => null,
			)
		);
		return $block_categories;
	}
}

CCBPress_Core_Blocks::init();
