<?php
/**
 * CCBPress Addon Handler
 *
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'CCBPress_Options' ) ) :

class CCBPress_Options {

	private $options;

	/**
	 * Create a new instance
	 */
	function __construct( $_args ) {

		$this->options = $_args;
		$this->hooks();

	}

	/**
	 * Setup our hooks
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 *
	 * @return void
	 */
	private function hooks() {

		add_filter( 'ccbpress_settings_page_tabs', array( $this, 'settings_page_tabs' ) );
		add_filter( 'ccbpress_settings_page_actions', array( $this, 'settings_page_actions' ) );
		add_filter( 'ccbpress_tools_page_tabs', array( $this, 'tools_page_tabs' ) );

	}

	/**
	 * Add the settings page tabs
	 *
	 * @since 1.0.0
	 *
	 * @param  array $tabs
	 *
	 * @return array
	 */
	public function settings_page_tabs( $tabs ) {

		if ( isset( $this->options['settings'] ) && is_array( $this->options['settings'] ) && isset( $this->options['settings']['tabs'] ) && is_array( $this->options['settings']['tabs'] ) ) {

			foreach( $this->options['settings']['tabs'] as $tab ) {
				$tabs[] = array(
					'tab_id'		=> $tab['tab_id'],
					'settings_id'	=> $tab['settings_id'],
					'title'			=> $tab['title'],
					'submit'		=> $tab['submit'],
				);
			}

		}

		return $tabs;

	}

	/**
	 * Add the settings page actions
	 *
	 * @since 1.0.0
	 *
	 * @param  array $actions
	 *
	 * @return array
	 */
	public function settings_page_actions( $actions ) {

		if ( isset( $this->options['settings'] ) && is_array( $this->options['settings'] ) && isset( $this->options['settings']['actions'] ) && is_array( $this->options['settings']['actions'] ) ) {

			$defaults = array(
				'class'		=> null,
				'target'	=> null,
			);

			foreach ( $this->options['settings']['actions'] as $action ) {

				$new_action = wp_parse_args( $action, $defaults );

				$actions[] = array(
					'tab_id'	=> $new_action['tab_id'],
					'class'		=> $new_action['class'],
					'link'		=> $new_action['link'],
					'target'	=> $new_action['target'],
					'title'		=> $new_action['title'],
				);

			}

		}

		return $actions;

	}

	/**
	 * Add the tools page tabs
	 *
	 * @since 1.3.4
	 *
	 * @param  array $tabs
	 *
	 * @return array
	 */
	public function tools_page_tabs( $tabs ) {

		if ( isset( $this->options['tools'] ) && is_array( $this->options['tools'] ) && isset( $this->options['tools']['tabs'] ) && is_array( $this->options['tools']['tabs'] ) ) {

			foreach( $this->options['tools']['tabs'] as $tab ) {
				$tabs[] = array(
					'tab_id'		=> $tab['tab_id'],
					'settings_id'	=> $tab['settings_id'],
					'title'			=> $tab['title'],
					'submit'		=> $tab['submit'],
				);
			}

		}

		return $tabs;

	}

}

endif;
