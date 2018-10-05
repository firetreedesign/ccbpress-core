<?php
/**
 * Online Giving Block
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
class CCBPress_Core_Online_Giving_Block {

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
            
        register_block_type( 'ccbpress/online-giving', array(
            'render_callback' => __CLASS__ . '::render',
        ) );
    }

    public static function render( $attributes ) {
        $ccb_api_url = CCBPress()->ccb->api_url;
        $ccb_online_giving_url = str_replace( 'api.php', 'w_give_online.php', $ccb_api_url );
        
        // Set the values passed from the widget/block options.
		$button_text = __('Give Now', 'ccbpress-core');
        if ( isset( $attributes['buttonText'] ) ) {
			$button_text = $attributes['buttonText'];
		}

		$style = '';
		if ( isset( $attributes['backgroundColor'] ) ) {
			$style .= 'background-color: ' . $attributes['backgroundColor'] . ';';
		}

		if ( isset( $attributes['textColor'] ) ) {
			$style .= 'color: ' . $attributes['textColor'] . ';';
		}

		ob_start();
		?>
		<div class="wp-block-ccbpress-online-giving">
			<form action="<?php esc_attr_e( $ccb_online_giving_url ); ?>" target="_blank">
			    <input type="submit" value="<?php esc_attr_e( $button_text ); ?>" style="<?php echo $style; ?>">
			</form>
		</div>
		<?php
		return ob_get_clean();
    }

}
CCBPress_Core_Online_Giving_Block::init();