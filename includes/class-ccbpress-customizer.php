<?php
/**
 * CCBPress Customizer Handler
 *
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'CCBPress_Customizer' ) ) :

class CCBPress_Customizer {

    /**
     * Create a new instance
     */
    function __construct() {
		$this->hooks();
    }

	private function hooks() {
		add_action( 'customize_register', array( $this, 'customize_register' ) );
	}

	public function customize_register( $wp_customize ) {
		$this->add_panel( $wp_customize );
	}

	private function add_panel( $wp_customize ) {
		$wp_customize->add_panel( 'ccbpress-core', array(
			'title'			=> __( 'CCBPress', 'ccbpress-core' ),
			'description'	=>  __( '<p>This screen is used to manage the CCBPress settings that affect the look of your website.</p>', 'ccbpress-core' ),
			'priority'		=> 160,
		) );
	}

}
new CCBPress_Customizer();

endif;
