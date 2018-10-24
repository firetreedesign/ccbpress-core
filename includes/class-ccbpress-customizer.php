<?php
/**
 * CCBPress Customizer Handler
 *
 * @since       1.0.0
 *
 * @package CCBPress Core
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

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
			$this->add_section( $wp_customize );
			$this->add_setting( $wp_customize );
			$this->add_control( $wp_customize );
    	}

    	private function add_panel( $wp_customize ) {
    		$wp_customize->add_panel( 'ccbpress-core', array(
    			'title'			=> __( 'CCBPress', 'ccbpress-core' ),
    			'description'	=>  __( '<p>This screen is used to manage the CCBPress settings that affect the look of your website.</p>', 'ccbpress-core' ),
    			'priority'		=> 160,
    		) );
		}
		
		private function add_section( $wp_customize ) {
			/**
			 * Church Community Builder Forms Section
			 */
			$wp_customize->add_section( 'ccbpress_ccb_links', array(
				'title'			=> __( 'Church Community Builder Links', 'ccbpress-core' ),
				'panel'			=> 'ccbpress-core',
				'priority'		=> 120,
			) );
		}

		private function add_setting( $wp_customize ) {
			/**
			 * Lightbox
			 */
			$wp_customize->add_setting( 'ccbpress_ccb_links_forms', array(
				'type'		=> 'option',
				'transport'	=> 'refresh',
				'default'	=> 'lightbox',
			) );
		}

		private function add_control( $wp_customize ) {
			/**
			 * Lightbox
			 */
			$wp_customize->add_control( 'ccbpress_ccb_links_forms', array(
				'label'			=> __( 'Form Links', 'ccbpress-core' ),
				'description'	=> __( '<p>How would you like to open links to Church Community Builder forms?</p>', 'ccbpress-core' ),
				'type'			=> 'radio',
				'section'		=> 'ccbpress_ccb_links',
				'choices'		=> array(
					'link'			=> __( 'Link', 'ccbpress-core' ),
					'lightbox'	=> __( 'Lightbox', 'ccbpress-core' ),
				),
			) );
		}

    }
    new CCBPress_Customizer();

endif;
