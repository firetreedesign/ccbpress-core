<?php
/**
 * Plugin Name: Church Data Connect for Church Community Builder
 * Plugin URI: https://churchdataconnect.com/
 * Description: Display information from Church Community Builder on your WordPress site.
 * Version: 1.5.1
 * Author: FireTree Design, LLC <info@firetreedesign.com>
 * Author URI: https://firetreedesign.com/
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
		 * CCBPress Background Get
		 *
		 * @var function
		 * @since 1.0.0
		 */
		public $get;

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
		public $version = '1.5.1';

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

				self::$instance = new CCBPress_Core();
				self::$instance->setup_constants();
				self::$instance->includes();

				self::$instance->transients = new CCBPress_Transients();
				self::$instance->ccb        = new CCBPress_Connection();
				self::$instance->get        = new CCBPress_Background_Get();

				self::$instance->init();
				self::$instance->transients->init();
				self::$instance->ccb->init();
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
				define( 'CCBPRESS_CORE_DB_VERSION', CCBPress()->version );
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
			require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/admin-settings.php';
			require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/settings/settings-ccb.php';
			require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/settings/settings-import.php';
			require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/settings/settings-ccbpress.php';
			require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/settings/settings-licenses.php';
			require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/tools/tools-cache.php';
			require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/tools/class-ccbpress-tools-cron.php';
			require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/admin-purge-cache.php';
			require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/widgets/widget-login.php';
			require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/widgets/widget-online-giving.php';
			require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/widgets/widget-group-info.php';
			require_once CCBPRESS_CORE_PLUGIN_DIR . 'lib/wp-background-processing/wp-async-request.php';
			require_once CCBPRESS_CORE_PLUGIN_DIR . 'lib/wp-background-processing/wp-background-process.php';
			require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/class-ccbpress-background-get.php';
			require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/import.php';
			require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/upgrades.php';
			require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/admin-rest-api.php';

			// Blocks.
			require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/class-ccbpress-core-blocks.php';

			if ( is_admin() ) {
				require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/admin-page-tabs.php';
				require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/admin-pages.php';
				require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/admin-scripts.php';
				require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/admin-styles.php';
				require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/admin/admin-dashboard.php';
			}
		}

		/**
		 * Initialize the class
		 *
		 * @since 1.1.15
		 *
		 * @return void
		 */
		public static function init() {
			// Load plugin text domain.
			add_action( 'plugins_loaded', array( 'CCBPress_Core', 'plugin_textdomain' ) );

			if ( false === get_option( 'ccbpress_import_in_progress', false ) ) {
				add_action( 'ccbpress_maintenance', array( 'CCBPress_Core', 'schedule_cron' ) );
			}

			if ( defined( 'DISABLE_WP_CRON' ) && true === DISABLE_WP_CRON ) {
				add_action( 'admin_notices', array( 'CCBPress_Core', 'cron_disabled' ) );
			}

			if ( defined( 'ALTERNATE_WP_CRON' ) && true === ALTERNATE_WP_CRON ) {
				add_action( 'admin_notices', array( 'CCBPress_Core', 'cron_alternate' ) );
			}
		}

		/**
		 * Schedule daily mantenance tasks
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public static function schedule_cron() {

			if ( false === wp_next_scheduled( 'ccbpress_maintenance' ) ) {
				wp_schedule_event( time() + 1800, 'hourly', 'ccbpress_maintenance' );
			}

			if ( false === wp_next_scheduled( 'ccbpress_import' ) && false === get_option( 'ccbpress_import_in_progress', false ) && CCBPress_Import::is_queue_empty() ) {
				wp_schedule_single_event( time(), 'ccbpress_import' );
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
			wp_clear_scheduled_hook( 'ccbpress_maintenance' );
			wp_clear_scheduled_hook( 'ccbpress_import' );
			wp_clear_scheduled_hook( 'ccbpress_transient_cache_cleanup' );
		}

		/**
		 * Display a warning that cron is disabled
		 *
		 * @since 1.3.0
		 *
		 * @return void
		 */
		public static function cron_disabled() {
			global $pagenow;

			if ( 'admin.php' !== $pagenow ) {
				return;
			}

			if ( ! isset( $_GET['page'] ) ) {
				return;
			}

			if ( 'ccbpress-settings' !== $_GET['page'] ) {
				return;
			}

			printf( '<div class="notice notice-warning"><p>%s %s</p></div>', esc_html__( 'Church Data Connect for Church Community Builder may not work properly because the DISABLE_WP_CRON constant is set to true.', 'ccbpress-core' ), sprintf( '<a href="#" class="ccbpress-cron-help">%s</a>', esc_html__( 'Get more info.', 'ccbpress-core' ) ) );
		}

		/**
		 * Display a warning that alternative cron is enabled
		 *
		 * @since 1.3.2
		 *
		 * @return void
		 */
		public static function cron_alternate() {
			global $pagenow;

			if ( 'admin.php' !== $pagenow ) {
				return;
			}

			if ( ! isset( $_GET['page'] ) ) {
				return;
			}

			if ( 'ccbpress-settings' !== $_GET['page'] ) {
				return;
			}

			printf( '<div class="notice notice-warning"><p>%s %s</p></div>', esc_html__( 'Church Data Connect for Church Community Builder may not work properly because the ALTERNATE_WP_CRON constant is set to true.', 'ccbpress-core' ), sprintf( '<a href="#" class="ccbpress-cron-help">%s</a>', esc_html__( 'Get more info.', 'ccbpress-core' ) ) );
		}

		/**
		 * Loads the plugin text domain for translation
		 *
		 * @since 1.1.15
		 *
		 * @return void
		 */
		public static function plugin_textdomain() {
			load_plugin_textdomain( 'ccbpress-core', false, dirname( plugin_basename( CCBPRESS_CORE_PLUGIN_FILE ) ) . '/languages' );
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
