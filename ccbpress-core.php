<?php
/**
 * Plugin Name: CCBPress Core
 * Plugin URI: http://ccbpress.com/
 * Description: Display information from Church Community Builder on your WordPress site.
 * Version: 0.9.6
 * Author: CCBPress <info@ccbpress.com>
 * Author URI: https://ccbpress.com/
 * Text Domain: ccbpress-core
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'CCBPress_Core' ) ) :

register_activation_hook( __FILE__, array('CCBPress_Core', 'on_activation') );
add_action( 'admin_init', array('CCBPress_Core', 'activation_redirect') );

/**
 * CCBPress Core class
 */
class CCBPress_Core {

     /**
      * @var CCBPress_Core The one true CCBPress_Core
      * @since 1.0.0
      */
     private static $instance;

     /**
	 * CCBPress Transients Object
	 *
	 * @var object
	 * @since 1.0.0
	 */
	public $transients;

    /**
    * CCBPress CCB Object
    *
    * @var object
    * @since 1.0.0
    */
   public $ccb;

   /**
    * CCBPress Version
    * @var string
    * @since 1.0.0
    */
   public $version = '0.9.6';

     /**
      * Main CCBPress_Core Instance
      *
      * Insures that only one instance of CCBPress_Core exists in memory at any
      * one time.
      *
      * @since 1.0
      * @static
      * @staticvar array $instance
      * @uses CCBPress_Core::includes() Include the required files
      * @see CCBPress_Core()
      * @return The one true CCBPress_Core
      */
     public static function instance() {

         if ( ! isset( self::$instance ) && ! ( self::$instance instanceof CCBPress_Core ) ) {

             self::$instance = new CCBPress_Core;
             self::$instance->setup_constants();
             self::$instance->includes();

             self::$instance->transients    = new CCBPress_Transients();
             self::$instance->ccb           = new CCBPress_Connection();

         }

         return self::$instance;

     }

	 public function on_activation() {
		 set_transient( 'ccbpress_activation_redirect', true, 30 );
	 }

	 public static function activation_redirect() {
		 // Bail if no activation redirect
		 if ( ! get_transient( 'ccbpress_activation_redirect' ) ) {
			 return;
		 }

		 // Delete the redirect transient
		 delete_transient( 'ccbpress_activation_redirect' );

		 // Bail if activating from network, or bulk
		 if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
			 return;
		 }

		 // Redirect to the Getting Started page
		 wp_safe_redirect( admin_url( add_query_arg( array( 'tab' => 'getting_started' ), add_query_arg( array( 'page' => 'ccbpress' ), 'index.php' ) ) ) );
	 }

     /**
      * Setup plugin constants
      *
      * @access private
      * @since 1.0.0
      * @return void
      */
     private function setup_constants() {

         // Plugin Database Version
         if ( ! defined( 'CCBPRESS_CORE_DB_VERSION' ) ) {
             define( 'CCBPRESS_CORE_DB_VERSION', '1.0.0' );
         }

		 // Plugin File
         if ( ! defined( 'CCBPRESS_CORE_PLUGIN_FILE' ) ) {
             define( 'CCBPRESS_CORE_PLUGIN_FILE', __FILE__ );
         }

         // Plugin Folder Path
         if ( ! defined( 'CCBPRESS_CORE_PLUGIN_DIR' ) ) {
             define( 'CCBPRESS_CORE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
         }

         // Plugin Folder URL
		if ( ! defined( 'CCBPRESS_CORE_PLUGIN_URL' ) ) {
			define( 'CCBPRESS_CORE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}

     }

     /**
      * Include required files
      *
      * @access private
      * @since 1.0
      * @return void
      */
     private function includes() {

         require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/class-ccbpress-transients.php';
         require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/class-ccbpress-connection.php';
		 require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/class-ccbpress-licenses.php';
		 require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/class-ccbpress-addon.php';
		 require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/class-ccbpress-options.php';
		 require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/class-ccbpress-template.php';
		 require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/class-ccbpress-customizer.php';
         require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/ccb-services.php';
         require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/schedule-get.php';
         require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/scripts.php';
         require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/styles.php';
		 require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/helpers.php';
         require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/settings/settings.php';
         require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/settings/settings-settings.php';
		 require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/settings/settings-tools.php';
		 require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/settings/settings-licenses.php';
         require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/admin-bar-menu.php';
         require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/purge-transients.php';
		 require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/admin-ajax.php';
		 require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/widgets/widget-login.php';
		 require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/widgets/widget-online-giving.php';
		 require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/widgets/widget-group-info.php';

        if ( is_admin() ) {
			require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/admin-page-tabs.php';
			require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/admin-pages.php';
            require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/admin-scripts.php';
            require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/admin-styles.php';
			require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/admin-dashboard.php';
        }

     }

}

endif; // End if class_exists check

/**
 * Initialize the CCBPress_Core class
 */
 function CCBPress() {
     return CCBPress_Core::instance();
 }
CCBPress();
