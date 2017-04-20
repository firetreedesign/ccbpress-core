<?php
/**
 * CCBPress Template Handler
 *
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'CCBPress_Template' ) ) :

class CCBPress_Template {

	private $template;
	private $plugin_path = CCBPRESS_CORE_PLUGIN_DIR;

    /**
     * Create a new instance
     */
    function __construct( $template, $plugin_path = FALSE ) {

		if ( ! isset( $template ) ) {
			return;
		}

		$this->template = $template;

		if ( $plugin_path ) {
			$this->plugin_path = $plugin_path;
		}

    }

	public function path() {

		$template_path = trailingslashit( $this->plugin_path ) . 'templates';
		$override_path = '/ccbpress';

		try {

			// Look for the template in the child theme directory.
			if ( file_exists( trailingslashit( get_stylesheet_directory() . $override_path ) . $this->template ) ) {
				return trailingslashit( get_stylesheet_directory() . $override_path ) . $this->template;
			}

			// Look for the template in the parent theme directory.
			if ( file_exists( trailingslashit( get_template_directory() . $override_path ) . $this->template ) ) {
				return trailingslashit( get_template_directory() . $override_path ) . $this->template;
			}

			// Look for the template in the plugin directory.
			if ( file_exists( trailingslashit( $template_path ) . $this->template ) ) {
				return trailingslashit( $template_path ) . $this->template;
			}

			return false;

		} catch ( Exception $e ) {

			// Return the data.
			return false;

		}

	}

}

endif;
