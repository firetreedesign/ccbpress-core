<?php
/**
 * Plugin Name: CCBPress Core
 * Plugin URI: http://ccbpress.com/
 * Description: Display information from Church Community Builder on your WordPress site.
 * Version: 0.9.8
 * Author: CCBPress <info@ccbpress.com>
 * Author URI: https://ccbpress.com/
 * Text Domain: ccbpress-core
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'CCBPress_Core' ) ) :

register_activation_hook( __FILE__, array( 'CCBPress_Core', 'create_tables' ) );

add_action( 'admin_notices', array('CCBPress_Core', 'activation_admin_notice') );
add_action( 'admin_init', array('CCBPress_Core', 'ignore_admin_notice') );

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
   public $version = '0.9.8';

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

    public static function activation_admin_notice() {

        // Get current user
        global $current_user;
        $user_id = $current_user->ID;

        // Get the current page to add the notice to
        global $pagenow;

        // Make sure we're on the plugins page
        if ( $pagenow == 'plugins.php' ) {

            // Output the activation banner if the user has not already dismissed our alert
            if ( ! get_user_meta( $user_id, 'ccbpress_core_activation_ignore_notice' ) ) :
            ?>
                <style>
                    .updated.ccbpress-core,
                    .updated.ccbpress-core * { -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; }
                    .updated.ccbpress-core { background-color: #fff; box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2); padding: 0 !important; margin: 10px 0 20px 0 !important; border: 0; }
                    .updated.ccbpress-core header { display: flex; width: 100%; align-content: stretch; position: relative; }
                    .updated.ccbpress-core header img,
                    .updated.ccbpress-core header div,
                    .updated.ccbpress-core header a { flex: 1 auto; padding: 10px; }
                    .updated.ccbpress-core header img { flex-grow: 0; flex-shrink: 0; height: 70px; width: auto; }
                    .updated.ccbpress-core header h3 { margin: 0; position: absolute; top: 50%; transform: translateY(-50%); }
                    .updated.ccbpress-core header h3 span { color: #ff5555; }
                    .updated.ccbpress-core header .dismiss { flex-grow: 0; flex-shrink: 0; width: 70px; height: 70px; background-color: #ff5555; color: #fff; line-height: 50px; text-align: center; }
                    .updated.ccbpress-core header .dismiss .dashicons { line-height: 50px; font-size: 24px; }
                    .updated.ccbpress-core .ccbpress-core-actions { display: flex; width: 100%; align-content: stretch; background-color: #f5f5f5; }
                    .updated.ccbpress-core .ccbpress-core-action { flex: 1 auto; height: 60px; position: relative; }
                    .updated.ccbpress-core .ccbpress-core-action.mailchimp { min-width: 33%; padding: 0 15px; overflow: hidden; }
                    .updated.ccbpress-core .ccbpress-core-action a { display: block; height: 60px; line-height: 60px; padding: 0 15px; color: #ff5555; }
                    .updated.ccbpress-core .ccbpress-core-action a:hover { background-color: rgba(0, 0, 0, 0.05); }
                    .updated.ccbpress-core .ccbpress-core-action a .dashicons { line-height: 60px; margin-right: 10px; }
                    .updated.ccbpress-core .ccbpress-core-action form { margin: 0; padding: 0; width: 90%; position: absolute; top: 50%; transform: translateY(-50%); }
                    .updated.ccbpress-core .ccbpress-core-action form p { margin: 0; padding: 0; font-size: 11px; width: 100%; color: #a3a3a3; }
                    .updated.ccbpress-core .ccbpress-core-action form input { flex: 1 auto; }
                    .updated.ccbpress-core .ccbpress-core-action form input[type=submit] { flex-grow: 0; flex-shrink: 0; }
                    .updated.ccbpress-core .ccbpress-core-newsletter-form { display: flex; width: 100%; }
                    @media screen and (max-width: 768px) {
                        .updated.ccbpress-core header img { display: none; }
                        .updated.ccbpress-core header h3 { margin-right: 70px; }
                        .updated.ccbpress-core .ccbpress-core-actions { flex-wrap: wrap; }
                        .updated.ccbpress-core .ccbpress-core-action { width: 100%; }
                    }
                </style>
                <div class="updated ccbpress-core">
                    <header>
                        <img src="<?php echo CCBPRESS_CORE_PLUGIN_URL; ?>/assets/images/ccbpress_mark.png" />
                        <div><h3><?php _e('Thanks for installing <span>CCBPress Core</span>', 'ccbpress-core'); ?></h3></div>
                        <?php printf( __('<a href="%1$s" class="dismiss"><span class="dashicons dashicons-no"></span></a>', 'ccbpress-core'), '?ccbpress_core_notice_ignore=0' ); ?>
                    </header>
                    <div class="ccbpress-core-actions">
                        <div class="ccbpress-core-action">
                            <a href="<?php echo admin_url( add_query_arg( array( 'tab' => 'getting_started' ), add_query_arg( array( 'page' => 'ccbpress' ), 'index.php' ) ) ); ?>">
                                <span class="dashicons dashicons-editor-help"></span><?php _e('How to set up CCB', 'ccbpress-core'); ?>
                            </a>
                        </div>
                        <div class="ccbpress-core-action">
                            <a href="<?php echo admin_url( add_query_arg( array( 'page' => 'ccbpress-settings' ), 'index.php' ) ); ?>">
                                <span class="dashicons dashicons-admin-settings"></span><?php _e('CCBPress Settings', 'ccbpress-core'); ?>
                            </a>
                        </div>
                        <div class="ccbpress-core-action">
                            <a href="https://ccbpress.com/docs/" target="_blank">
                                <span class="dashicons dashicons-book"></span><?php _e('Documentation', 'ccbpress-core'); ?>
                            </a>
                        </div>
                        <div class="ccbpress-core-action mailchimp">
                            <!-- Begin MailChimp Signup Form -->
                            <form action="//firetreedesign.us8.list-manage.com/subscribe/post?u=cb835fbfeabd73de481b4c161&amp;id=504bd42694" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>

                                <div class="ccbpress-core-newsletter">
                                    <p><?php _e('Stay informed of important news and updates.', 'ccbpress-core'); ?></p>
                                    <div class="ccbpress-core-newsletter-form">
                                        <input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL" placeholder="Email (required)">
                                        <input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button">
                                    </div>
                                    <div class="ccbpress-core-newsletter-confirmation" style="display: none;">
                                        <p><?php _e('Thank you for subscribing!', 'ccbpress-core'); ?></p>
                                    </div>
                                </div>

                                <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_cb835fbfeabd73de481b4c161_504bd42694" tabindex="-1" value=""></div>
                            </form>
                            <script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script>
                            <script type='text/javascript'>
                            (function($) {
                                window.fnames = new Array();window.ftypes = new Array();
                                fnames[0]='EMAIL';ftypes[0]='email';
                                $( 'form[name="mc-embedded-subscribe-form"]' ).on( 'submit', function () {
                                    var email_field = $( this ).find( '#mce-EMAIL' ).val();
                                    if ( ! email_field ) {
                                        return false;
                                    }
                                    $( this ).find( '.ccbpress-core-newsletter-confirmation' ).delay(500).slideDown();
                                    $( this ).find( '.ccbpress-core-newsletter-form' ).delay(500).slideUp();
                                } );
                            }(jQuery));
                            var $mcj = jQuery.noConflict(true);
                            </script>
                            <!--End mc_embed_signup-->
                        </div>
                    </div>
                </div>
            <?php
            endif;
        }

    }

    public static function ignore_admin_notice() {

        // Get the global user
        global $current_user;
        $user_id = $current_user->ID;

        if ( isset( $_GET['ccbpress_core_notice_ignore'] ) && '0' == $_GET['ccbpress_core_notice_ignore'] ) {
            add_user_meta( $user_id, 'ccbpress_core_activation_ignore_notice', 'true', true );
        }

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
		 require_once CCBPRESS_CORE_PLUGIN_DIR . 'includes/group_profiles-db.php';

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
