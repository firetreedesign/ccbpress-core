<?php
/**
 * Plugin Name: CCBPress Core
 * Plugin URI: http://ccbpress.com/
 * Description: Display information from Church Community Builder on your WordPress site.
 * Version: 1.0.2
 * Author: CCBPress <info@ccbpress.com>
 * Author URI: https://ccbpress.com/
 * Text Domain: ccbpress-core
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package CCBPress_Core
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'CCBPress_Core' ) ) :

	register_activation_hook( __FILE__, array( 'CCBPress_Core', 'create_tables' ) );
	register_activation_hook( __FILE__, array( 'CCBPress_Core', 'schedule_cron' ) );
	register_deactivation_hook( __FILE__, array( 'CCBPress_Core', 'unschedule_cron' ) );

	/**
	 * CCBPress Core class
	 */
	class CCBPress_Core {

	    /**
	     * Instance
	     *
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
	     * CCBPress Sync Object
	     *
	     * @var object
	     * @since 1.0.0
	     */
	    public $sync;

		/**
	     * CCBPress Version
	     *
	     * @var string
	     * @since 1.0.0
	     */
	    public $version = '1.0.2';

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

	            self::$instance->transients = new CCBPress_Transients();
	            self::$instance->ccb        = new CCBPress_Connection();
				self::$instance->sync       = new CCBPress_Sync();

	        }

	        return self::$instance;

	    }

		/**
		 * Setup plugin constants
		 *
		 * @access private
		 * @since 1.0.0
		 * @return void
		 */
		private function setup_constants() {

	        // Plugin Database Version.
	        if ( ! defined( 'CCBPRESS_CORE_DB_VERSION' ) ) {
	            define( 'CCBPRESS_CORE_DB_VERSION', '1.0.0' );
	        }

			// Plugin File.
	        if ( ! defined( 'CCBPRESS_CORE_PLUGIN_FILE' ) ) {
	            define( 'CCBPRESS_CORE_PLUGIN_FILE', __FILE__ );
	        }

	        // Plugin Folder Path.
	        if ( ! defined( 'CCBPRESS_CORE_PLUGIN_DIR' ) ) {
	            define( 'CCBPRESS_CORE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	        }

	        // Plugin Folder URL.
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
	        require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/styles.php';
			require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/helpers.php';
			require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/maintenance.php';
	        require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/admin-settings.php';
	        require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/settings/settings-ccb.php';
			require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/settings/settings-sync.php';
	        require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/settings/settings-ccbpress.php';
			require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/settings/settings-licenses.php';
	        require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/admin-bar-menu.php';
	        require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/admin-purge-cache.php';
			require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/admin-ajax.php';
			require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/widgets/widget-login.php';
			require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/widgets/widget-online-giving.php';
			require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/widgets/widget-group-info.php';
			require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/group_profiles-db.php';
	        require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/event_profiles-db.php';
			require_once CCBPRESS_CORE_PLUGIN_DIR . 'lib/wp-background-processing/wp-async-request.php';
			require_once CCBPRESS_CORE_PLUGIN_DIR . 'lib/wp-background-processing/wp-background-process.php';
			require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/class-ccbpress-sync.php';

	        if ( is_admin() ) {
				require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/admin-page-tabs.php';
				require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/admin-pages.php';
	            require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/admin-scripts.php';
	            require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/admin-styles.php';
				require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/admin-dashboard.php';
	        }

	    }

		/**
		 * Create the custom tables needed for our plugin
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public static function create_tables() {

			 require_once plugin_dir_path( __FILE__ ) . 'includes/group_profiles-db.php';
			 $group_profiles_db = new CCBPress_Group_Profiles_DB();
			 $group_profiles_db->create_table();
			 unset( $group_profiles_db );

	         require_once plugin_dir_path( __FILE__ ) . 'includes/event_profiles-db.php';
			 $event_profiles_db = new CCBPress_Event_Profiles_DB();
			 $event_profiles_db->create_table();
			 unset( $event_profiles_db );

		}

		/**
		 * Schedule daily mantenance tasks
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public static function schedule_cron() {

			if ( false === wp_next_scheduled( 'ccbpress_daily_maintenance' ) ) {

				$timestamp = strtotime( 'midnight ' . get_option( 'timezone_string' ) );
				if ( $timestamp < current_time( 'timestamp' ) ) {
					$timestamp = strtotime( '+1 day', $timestamp );
				}

				wp_schedule_event( $timestamp, 'daily', 'ccbpress_daily_maintenance' );

			}

		}

		/**
		 * Unschedule daily mantenance tasks
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public static function unschedule_cron() {
			wp_clear_scheduled_hook( 'ccbpress_daily_maintenance' );
			wp_clear_scheduled_hook( 'ccbpress_transient_cache_cleanup' );
		}

	}

endif; // End if class_exists check.

/**
 * Initialize the CCBPress_Core class
 */
function ccbpress() {
	return CCBPress_Core::instance();
}
ccbpress();
