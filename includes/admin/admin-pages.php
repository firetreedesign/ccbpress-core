<?php
/**
 * Admin Pages class
 *
 * @package CCBPress_Core
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * CCBPress_Admin_Pages Class
 *
 * @since 1.0.0
 */
class CCBPress_Admin_Pages {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'admin_menus' ) );
        add_action( 'admin_head', array( $this, 'admin_head' ) );
    }

    /**
     * Register the Admin Pages
     *
     * @access public
     * @since 1.0.0
     * @return void
     */
    public function admin_menus() {

        // Getting Started Page
        add_menu_page(
            __( 'CCBPress Options', 'ccbpress-core' ),
            __( 'CCBPress', 'ccbpress-core' ),
            'administrator',
            'ccbpress',
            array( $this, 'welcome_page' ),
            "data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+PHN2ZyAgIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIgICB4bWxuczpjYz0iaHR0cDovL2NyZWF0aXZlY29tbW9ucy5vcmcvbnMjIiAgIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyIgICB4bWxuczpzdmc9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiAgIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgICB4bWxuczpzb2RpcG9kaT0iaHR0cDovL3NvZGlwb2RpLnNvdXJjZWZvcmdlLm5ldC9EVEQvc29kaXBvZGktMC5kdGQiICAgeG1sbnM6aW5rc2NhcGU9Imh0dHA6Ly93d3cuaW5rc2NhcGUub3JnL25hbWVzcGFjZXMvaW5rc2NhcGUiICAgd2lkdGg9IjMwNy44MjgxMiIgICBoZWlnaHQ9IjI3NS43NDE5NCIgICB2aWV3Qm94PSIwIDAgMzA3LjgyODEyIDI3NS43NDE5NCIgICBpZD0ic3ZnMiIgICB2ZXJzaW9uPSIxLjEiICAgaW5rc2NhcGU6dmVyc2lvbj0iMC45MSByMTM3MjUiICAgc29kaXBvZGk6ZG9jbmFtZT0iY2NicHJlc3NfY2hldnJvbl9tYXJrLnN2ZyIgICBpbmtzY2FwZTpleHBvcnQtZmlsZW5hbWU9Ii9Vc2Vycy9kYW5pZWxtaWxuZXIvRHJvcGJveC9maXJldHJlZS9jY2JwcmVzcy9sb2dvL2NjYnByZXNzLnBuZyIgICBpbmtzY2FwZTpleHBvcnQteGRwaT0iMTI4LjI5NjIyIiAgIGlua3NjYXBlOmV4cG9ydC15ZHBpPSIxMjguMjk2MjIiPiAgPGRlZnMgICAgIGlkPSJkZWZzNCI+ICAgIDxpbmtzY2FwZTpwZXJzcGVjdGl2ZSAgICAgICBzb2RpcG9kaTp0eXBlPSJpbmtzY2FwZTpwZXJzcDNkIiAgICAgICBpbmtzY2FwZTp2cF94PSIwIDogMTQ1LjU2MTU5IDogMSIgICAgICAgaW5rc2NhcGU6dnBfeT0iMCA6IDk5OS45OTk5OCA6IDAiICAgICAgIGlua3NjYXBlOnZwX3o9IjE0MDIuNjc4MSA6IDE0NS41NjE1OSA6IDEiICAgICAgIGlua3NjYXBlOnBlcnNwM2Qtb3JpZ2luPSI3MDEuMzM5MDQgOiA5Ny4wNDEwNTUgOiAxIiAgICAgICBpZD0icGVyc3BlY3RpdmUzODA4IiAvPiAgICA8aW5rc2NhcGU6cGF0aC1lZmZlY3QgICAgICAgZWZmZWN0PSJwb3dlcnN0cm9rZSIgICAgICAgaWQ9InBhdGgtZWZmZWN0NDE1NCIgICAgICAgaXNfdmlzaWJsZT0idHJ1ZSIgICAgICAgb2Zmc2V0X3BvaW50cz0iMCwwLjUiICAgICAgIHNvcnRfcG9pbnRzPSJ0cnVlIiAgICAgICBpbnRlcnBvbGF0b3JfdHlwZT0iTGluZWFyIiAgICAgICBpbnRlcnBvbGF0b3JfYmV0YT0iMC4yIiAgICAgICBzdGFydF9saW5lY2FwX3R5cGU9Inplcm93aWR0aCIgICAgICAgbGluZWpvaW5fdHlwZT0icm91bmQiICAgICAgIG1pdGVyX2xpbWl0PSI0IiAgICAgICBlbmRfbGluZWNhcF90eXBlPSJ6ZXJvd2lkdGgiICAgICAgIGN1c3BfbGluZWNhcF90eXBlPSJyb3VuZCIgLz4gIDwvZGVmcz4gIDxzb2RpcG9kaTpuYW1lZHZpZXcgICAgIGlkPSJiYXNlIiAgICAgcGFnZWNvbG9yPSIjZmZmZmZmIiAgICAgYm9yZGVyY29sb3I9IiM2NjY2NjYiICAgICBib3JkZXJvcGFjaXR5PSIxLjAiICAgICBpbmtzY2FwZTpwYWdlb3BhY2l0eT0iMC4wIiAgICAgaW5rc2NhcGU6cGFnZXNoYWRvdz0iMiIgICAgIGlua3NjYXBlOnpvb209IjAuNyIgICAgIGlua3NjYXBlOmN4PSItMC40MjM2OTk0NyIgICAgIGlua3NjYXBlOmN5PSI2OTguOTI5NzciICAgICBpbmtzY2FwZTpkb2N1bWVudC11bml0cz0icHgiICAgICBpbmtzY2FwZTpjdXJyZW50LWxheWVyPSJsYXllcjEiICAgICBzaG93Z3JpZD0iZmFsc2UiICAgICB1bml0cz0icHgiICAgICBpbmtzY2FwZTp3aW5kb3ctd2lkdGg9IjI1MTMiICAgICBpbmtzY2FwZTp3aW5kb3ctaGVpZ2h0PSIxMzk1IiAgICAgaW5rc2NhcGU6d2luZG93LXg9IjQ3IiAgICAgaW5rc2NhcGU6d2luZG93LXk9IjAiICAgICBpbmtzY2FwZTp3aW5kb3ctbWF4aW1pemVkPSIxIiAgICAgZml0LW1hcmdpbi10b3A9IjEwIiAgICAgZml0LW1hcmdpbi1sZWZ0PSIxMCIgICAgIGZpdC1tYXJnaW4tYm90dG9tPSIxMCIgICAgIGZpdC1tYXJnaW4tcmlnaHQ9IjEwIj4gICAgPGlua3NjYXBlOmdyaWQgICAgICAgdHlwZT0ieHlncmlkIiAgICAgICBpZD0iZ3JpZDMzNDgiICAgICAgIG9yaWdpbng9IjE4MS4wNTY3NiIgICAgICAgb3JpZ2lueT0iMTU2OS41ODQ0IiAvPiAgPC9zb2RpcG9kaTpuYW1lZHZpZXc+ICA8bWV0YWRhdGEgICAgIGlkPSJtZXRhZGF0YTciPiAgICA8cmRmOlJERj4gICAgICA8Y2M6V29yayAgICAgICAgIHJkZjphYm91dD0iIj4gICAgICAgIDxkYzpmb3JtYXQ+aW1hZ2Uvc3ZnK3htbDwvZGM6Zm9ybWF0PiAgICAgICAgPGRjOnR5cGUgICAgICAgICAgIHJkZjpyZXNvdXJjZT0iaHR0cDovL3B1cmwub3JnL2RjL2RjbWl0eXBlL1N0aWxsSW1hZ2UiIC8+ICAgICAgICA8ZGM6dGl0bGU+PC9kYzp0aXRsZT4gICAgICA8L2NjOldvcms+ICAgIDwvcmRmOlJERj4gIDwvbWV0YWRhdGE+ICA8ZyAgICAgaW5rc2NhcGU6bGFiZWw9IkxheWVyIDEiICAgICBpbmtzY2FwZTpncm91cG1vZGU9ImxheWVyIiAgICAgaWQ9ImxheWVyMSIgICAgIHRyYW5zZm9ybT0idHJhbnNsYXRlKC05Mi4xOTIwNCwtMjAxMC4xMzkxKSI+ICAgIDxwYXRoICAgICAgIHN0eWxlPSJmaWxsOiNmZjU1NTU7ZmlsbC1vcGFjaXR5OjE7c3Ryb2tlOm5vbmUiICAgICAgIGQ9Im0gMjQ2LjEwNjEsMjAyMC4xMzkxIGMgLTM0LjEwODk3LDAgLTY4LjIxODA0LDEuMDc5NiAtNzEuOTU3MDMsMy4yMzgzIC03LjQ3Nzk4LDQuMzE3NCAtNzEuOTU3MDMsMTE1Ljk5OCAtNzEuOTU3MDMsMTI0LjYzMjggMCw4LjYzNDggNjQuNDc5MDUsMTIwLjMxNTQgNzEuOTU3MDMsMTI0LjYzMjggNy40Nzc5OSw0LjMxNzQgMTM2LjQzNjA4LDQuMzE3NCAxNDMuOTE0MDYsMCA3LjQ3Nzk5LC00LjMxNzQgNzEuOTU3MDMsLTExNS45OTggNzEuOTU3MDMsLTEyNC42MzI4IDAsLTguNjM0OCAtNjQuNDc5MDQsLTEyMC4zMTU0IC03MS45NTcwMywtMTI0LjYzMjggLTMuNzM4OTksLTIuMTU4NyAtMzcuODQ4MDUsLTMuMjM4MyAtNzEuOTU3MDMsLTMuMjM4MyB6IG0gLTczLjgxODM2LDQ5LjY0MDYgNDguOTQ3MjcsMCA1Ny43MDcwMyw3OC4yMzA1IC01Ny43MDcwMyw3OC4yMzI0IC00OC45NDcyNywwIDU3LjcwNTA4LC03OC4yMzI0IC01Ny43MDUwOCwtNzguMjMwNSB6IG0gNjAuOTgyNDIsMCA0OC45NDkyMiwwIDU3LjcwNTA4LDc4LjIzMDUgLTU3LjcwODk4LDc4LjIzMjQgLTQ4Ljk0NTMyLDAgNTcuNzAzMTMsLTc4LjIzMjQgLTU3LjcwMzEzLC03OC4yMzA1IHoiICAgICAgIGlkPSJwYXRoNDczNCIgICAgICAgaW5rc2NhcGU6Y29ubmVjdG9yLWN1cnZhdHVyZT0iMCIgLz4gIDwvZz4gIDxnICAgICBpbmtzY2FwZTpncm91cG1vZGU9ImxheWVyIiAgICAgaWQ9ImxheWVyNCIgICAgIGlua3NjYXBlOmxhYmVsPSI2LVBvaW50IENoZXZyb24iICAgICB0cmFuc2Zvcm09InRyYW5zbGF0ZSgxODEuMDU2NzYsLTE2NjQuOTY1NykiIC8+PC9zdmc+"
        );

        // Remove duplicate menu and add Welcome menu subpage
        add_submenu_page(
    	    'ccbpress',
    	    __( 'Welcome', 'ccbpress-core' ),
    	    __( 'Welcome', 'ccbpress-core' ),
    	    'administrator',
    	    'ccbpress',
    	    array( $this, 'welcome_page' )
        );

        // Settings Page
		global $ccbpress_settings_help_page;
		$ccbpress_settings_help_page = add_submenu_page(
    	    'ccbpress',
    	    __( 'Settings', 'ccbpress-core' ),
    	    __( 'Settings', 'ccbpress-core' ),
    	    'administrator',
    	    'ccbpress-settings',
    	    array( $this, 'settings_page' )
        );
		add_action( 'load-' . $ccbpress_settings_help_page, array( $this, 'settings_page_help' ) );

        // Tools Page
		global $ccbpress_tools_help_page;
		$ccbpress_tools_help_page = add_submenu_page(
    	    'ccbpress',
    	    __( 'Tools', 'ccbpress-core' ),
    	    __( 'Tools', 'ccbpress-core' ),
    	    'administrator',
    	    'ccbpress-tools',
    	    array( $this, 'tools_page' )
        );
		add_action( 'load-' . $ccbpress_tools_help_page, array( $this, 'tools_page_help' ) );

    }

    /**
	 * Admin Page Styles
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function admin_head() {
		?>
		<style type="text/css" media="screen">
		.about-wrap .ccbpress-badge {
			position: absolute;
			top: 0;
			right: 0;
			width: 100px;
		}
		.about-wrap.ccbpress > h1 {
			margin-bottom: 15px;
		}
		</style>
		<?php
	}

    /**
     * Render Getting Started Page
     *
     * @access public
     * @since 1.0.0
     * @return void
     */
    public function welcome_page() {
		wp_enqueue_script('ccbpress-core-beacon');
		$active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'welcome';
        ?>
        <div class="wrap about-wrap ccbpress">
            <h1><?php _e( 'Welcome to CCBPress', 'ccbpress-core' ); ?></h1>
            <div class="about-text">
				<?php _e( 'Thank you for using CCBPress. CCBPress allows you to display content from Church Community Builder on your WordPress site.', 'ccbpress-core' ); ?>
			</div>
            <div class="ccbpress-badge"><img src="<?php echo CCBPRESS_CORE_PLUGIN_URL . 'assets/images/ccbpress_mark.png'; ?>" alt="<?php _e( 'CCBPress', 'ccbpress-core' ); ?>" / ></div>
			<div class="wp-filter">
				<ul class="filter-links">
					<li>
						<a class="<?php echo ( $active_tab === 'welcome' ? 'current' : '' ); ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'ccbpress' ), 'admin.php' ) ) ); ?>">
	                    	<?php _e( 'Welcome', 'ccbpress-core' ); ?>
	                	</a>
					</li>
					<li>
						<a class="<?php echo ( $active_tab === 'getting_started' ? 'current' : '' ); ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'tab' => 'getting_started' ), add_query_arg( array( 'page' => 'ccbpress' ), 'admin.php' ) ) ) ); ?>">
		                    <?php _e( 'Getting Started', 'ccbpress-core' ); ?></span>
		                </a>
					</li>
				</ul>
			</div>
			<?php
			switch ( $active_tab ) {
				case 'getting_started':
					echo $this->getting_started_content();
					break;
				default:
					echo $this->welcome_content();
					break;
			}
			?>
        </div>
        <?php
    }

	private function welcome_content() {
		?>
		<div class="feature-section three-col">
			<div class="col">
				<div class="svg-container">
					<span class="dashicons dashicons-lightbulb"></span>
				</div>
				<h3><?php _e('Less Work', 'ccbpress-core'); ?></h3>
				<p><?php _e('CCBPress eliminates the manual work of entering and changing data on your website that already exists in Church Community Builder (CCB).', 'ccbpress-core'); ?></p>
			</div>
			<div class="col">
				<div class="svg-container">
					<span class="dashicons dashicons-download"></span>
				</div>
				<h3><?php _e('Cached Locally', 'ccbpress-core'); ?></h3>
				<p><?php _e('Everything is cached locally on your WordPress site when possible to reduce the strain on Church Community Builder and display your pages as fast as possible.', 'ccbpress-core'); ?></p>
			</div>
			<div class="col">
				<div class="svg-container">
					<span class="dashicons dashicons-plus-alt"></span>
				</div>
				<h3><?php _e('Extend with Add-ons', 'ccbpress-core'); ?></h3>
				<p><?php _e('We have several add-ons available to extend CCBPress with additional features.', 'ccbpress-core'); ?></p>
				<p><strong><?php _e('Available Soon!', 'ccbpress-core'); ?></strong></p>
			</div>
		</div>

		<h3><?php _e('Dashboard Widget', 'ccbpress-core'); ?></h3>
		<p><?php _e('At a glance, you can keep tabs on CCBPress.', 'ccbpress-core'); ?></p>
		<div class="feature-section">
			<div class="feature-section-media">
				<img src="<?php echo esc_url( CCBPRESS_CORE_PLUGIN_URL . '/assets/images/api_status.png' ); ?>" />
			</div>
			<div class="feature-section-content">
				<h4><?php _e('API Status', 'ccbpress-core'); ?></h4>
				<p><?php _e('The Church Community Builder API status is displayed on your WordPress Dashboard. Easily keep track of your API usage throughout the day.', 'ccbpress-core'); ?></p>
			</div>
		</div>
		<h3><?php _e('Widgets', 'ccbpress-core'); ?></h3>
		<p><?php _e('We have widgets to display a variety of information.', 'ccbpress-core'); ?></p>
		<div class="feature-section two-col">
			<div class="col">
				<div class="media-container">
					<img src="<?php echo esc_url( CCBPRESS_CORE_PLUGIN_URL . '/assets/images/group_info_widget.png' ); ?>" />
				</div>
				<h4><?php _e('Group Information Widget', 'ccbpress-core'); ?></h4>
				<p><?php _e('The Group Information Widget displays details about a specific group from Church Community Builder.', 'ccbpress-core'); ?></p>
			</div>
			<div class="col">
				<div class="media-container">
					<img src="<?php echo esc_url( CCBPRESS_CORE_PLUGIN_URL . '/assets/images/online_giving_widget.png' ); ?>" />
				</div>
				<h4><?php _e('Online Giving Widget', 'ccbpress-core'); ?></h4>
				<p><?php _e('Church Community Builder has a very nice Online Giving page. This widget allows you to easily place a link to that page anywhere you\'d like.', 'ccbpress-core'); ?></p>
			</div>
			<div class="col">
				<div class="media-container">
					<img src="<?php echo esc_url( CCBPRESS_CORE_PLUGIN_URL . '/assets/images/ccb_login_widget.png' ); ?>" />
				</div>
				<h4><?php _e('CCB Login', 'ccbpress-core'); ?></h4>
				<p class="notes"><?php _e('The CCB Login widget allows you to place a form on your site that lets your users log in to Church Community Builder from your website. It passes their login info to CCB and opens CCB in a new window.', 'ccbpress-core'); ?></p>
			</div>
		</div>
		<?php
	}

	private function getting_started_content() {
		$services = apply_filters( 'ccbpress_ccb_services', array() );
		sort( $services );
		?>
		<!-- Begin MailChimp Signup Form -->
		<style>
		.ccbpress-core-newsletter { clear: both; padding: 20px; background-color: #e7e7e7; color: #777; max-width: 700px; }
		.ccbpress-core-newsletter p { font-style: italic; margin: 0; }
		.ccbpress-core-newsletter-field { display: inline-block; margin-top: 10px; }
		.ccbpress-core-newsletter-field .button { vertical-align: initial; }
		</style>
		<form action="//firetreedesign.us8.list-manage.com/subscribe/post?u=cb835fbfeabd73de481b4c161&amp;id=504bd42694" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>

			<div class="ccbpress-core-newsletter">
				<div class="ccbpress-core-newsletter-form">
					<p><?php _e('Sign up for the CCBPress newsletter to stay informed of important news and updates.', 'ccbpress-core'); ?></p>
					<div class="ccbpress-core-newsletter-field"><input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL" placeholder="Email (required)"></div>
					<div class="ccbpress-core-newsletter-field"><input type="text" value="" name="FNAME" class="" id="mce-FNAME" placeholder="First Name"></div>
					<div class="ccbpress-core-newsletter-field"><input type="text" value="" name="LNAME" class="" id="mce-LNAME" placeholder="Last Name"></div>
					<div class="ccbpress-core-newsletter-field"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
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
			fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';
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
		<h3><?php _e('Connecting to Church Community Builder', 'ccbpress-core'); ?></h3>
		<p><?php _e('Before you can use the plugin, you need to provide it with information needed to connect to your Church Community Builder account.', 'ccbpress-core'); ?></p>
		<div class="feature-section">
			<div class="feature-section-media">
				<img src="<?php echo esc_url( CCBPRESS_CORE_PLUGIN_URL . '/assets/images/getting_started_api_user.png' ); ?>" />
			</div>
			<div class="feature-section-content">
				<h3><?php _e('1. Creating a Church Community Builder API User', 'ccbpress-core'); ?></h3>
				<p><?php _e('The API User is created from inside the API Admin section of Church Community Builder. Church Community Builder has some excellent documentation on doing this.', 'ccbpress-core'); ?></p>
				<p><?php _e('Your API User must have permission to use the following services:', 'ccbpress-core'); ?></p>
				<ul>
				<?php foreach( $services as $service ) : ?>
				    <li><?php echo $service; ?></li>
				<?php endforeach; ?>
				<?php if ( count( $services ) == 0 ) : ?>
					<li><?php _e('There are no services registered with CCBPress.', 'ccbpress-core'); ?></li>
				<?php endif; ?>
				</ul>
				<p class="notes"><?php _e('This list is automatically updated based on the add-ons that are installed and the services that they require.', 'ccbpress-core'); ?></p>
				<p><a class="button button-secondary" href="http://support.churchcommunitybuilder.com/customer/portal/articles/640595" target="_blank"><?php _e('Creating an API User and Assigning Services', 'ccbpress-core'); ?> <span class="dashicons dashicons-external"></span></a></p>
			</div>
		</div>
		<div class="feature-section">
			<div class="feature-section-media">
				<img src="<?php echo esc_url( CCBPRESS_CORE_PLUGIN_URL . '/assets/images/getting_started_connection_settings.png' ); ?>" />
			</div>
			<div class="feature-section-content">
				<h3><?php _e('2. Entering Your API User Information', 'ccbpress-core'); ?></h3>
				<p><?php _e('Next, you will need to visit the Church Community Builder tab of the Settings page and enter the Connection Settings for your API User.', 'ccbpress-core'); ?></p>
				<p><?php _e('You will need to have the following information:', 'ccbpress-core'); ?></p>
				<ul>
					<li><?php _e('The URL you use to access your Church Community Builder site.', 'ccbpress-core'); ?></li>
					<li><?php _e('The API Username that you created.', 'ccbpress-core'); ?></li>
					<li><?php _e('The Password for your API User.', 'ccbpress-core'); ?></li>
				</ul>
				<p><a class="button button-secondary" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'ccbpress-settings' ), 'admin.php' ) ) ); ?>"><?php _e('Enter Your Connection Settings', 'ccbpress-core'); ?></a></p>
			</div>
		</div>
		<div class="feature-section two-col">
			<div class="col">
				<h3><?php _e('3. Add Some Widgets', 'ccbpress-core'); ?></h3>
				<p><?php _e('You are now ready to use any of the widgets that come with CCBPress.', 'ccbpress-core'); ?></p>
				<p><a class="button button-secondary" href="<?php echo esc_url( admin_url( 'widgets.php' ) ); ?>"><?php _e('Manage Widgets', 'ccbpress-core'); ?></a></p>
			</div>
			<div class="col">
				<h3><?php _e('Browse Our Add-ons', 'ccbpress-core'); ?></h3>
				<p><?php _e('Feel free to browse our add-ons to add additional functionality to CCBPress.', 'ccbpress-core'); ?></p>
				<p><strong><?php _e('Available Soon!', 'ccbpress-core'); ?></strong></p>
			</div>
		</div>
		<?php
	}

    /**
     * Render CCB Connection Page
     *
     * @access public
     * @since 1.0.0
     * @return void
     */
    public function settings_page() {
		wp_enqueue_script('ccbpress-core-beacon');
		$all_tabs = apply_filters( 'ccbpress_settings_page_tabs', array() );
		$active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $all_tabs[0]['tab_id'];

		$all_tab_actions = apply_filters( 'ccbpress_settings_page_actions', array() );
		$has_tab_actions = FALSE;
		foreach ( $all_tab_actions as $tab_action ) {
			if ( isset( $tab_action['tab_id'] ) && $tab_action['tab_id'] == $active_tab ) {
				$has_tab_actions = TRUE;
			}
		}
		?>
        <div class="wrap">
			<h1><?php _e('Settings', 'ccbpress-core'); ?></h1>
			<div class="wp-filter">
				<ul class="filter-links">
					<?php foreach ( $all_tabs as $tab ) : ?>
						<li class="<?php echo esc_attr( $tab['tab_id'] ); ?>">
							<a class="<?php echo ( $active_tab === $tab['tab_id'] ? ' current' : '' ); ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'tab' => $tab['tab_id'] ), add_query_arg( array( 'page' => 'ccbpress-settings' ), 'admin.php' ) ) ) ); ?>">
			                    <?php echo $tab['title']; ?>
			                </a>
						</li>
					<?php endforeach; ?>
				</ul>
				<?php if ( $has_tab_actions ) : ?>
					<div class="ccbpress_tab_actions">
					<?php foreach ( $all_tab_actions as $tab_action ) : ?>
						<?php if ( isset( $tab_action['tab_id'] ) && $tab_action['tab_id'] == $active_tab ) : ?>
							<a class="button button-<?php echo $tab_action['type']; ?><?php echo ( is_null( $tab_action['class'] ) ) ? '' : ' ' . $tab_action['class']; ?>" href="<?php echo esc_url( $tab_action['link'] ); ?>"<?php echo ( is_null( $tab_action['target'] ) ) ? '' : ' target="' . $tab_action['target'] . '"'; ?>><?php echo $tab_action['title']; ?></a>
						<?php endif; ?>
					<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
			<div id="ccbpress_tab_container" class="metabox-holder">
				<div class="postbox">
					<div class="inside">
		    			<form method="post" action="options.php">
		    				<table class="form-table">
								<?php
								foreach ( $all_tabs as $tab ) {
									if ( isset( $tab['tab_id'] ) && isset( $tab['settings_id'] ) && $tab['tab_id'] == $active_tab ) {
										settings_fields( $tab['settings_id'] );
										do_settings_sections( $tab['settings_id'] );
										if ( TRUE === $tab['submit'] ) {
											submit_button();
										}
										settings_errors();
									}
								}
								?>
		    				</table>
		    			</form>
					</div>
				</div>
    		</div><!-- #tab_container-->
        </div>
        <?php
    }

    /**
     * Render Tools Page
     *
     * @access public
     * @since 1.0.0
     * @return void
     */
    public function tools_page() {
		wp_enqueue_script('ccbpress-core-beacon');
		$all_tabs = apply_filters( 'ccbpress_tools_page_tabs', array() );
		$active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $all_tabs[0]['tab_id'];

		$all_tab_actions = apply_filters( 'ccbpress_tools_page_actions', array() );
		$has_tab_actions = FALSE;
		foreach ( $all_tab_actions as $tab_action ) {
			if ( isset( $tab_action['tab_id'] ) && $tab_action['tab_id'] == $active_tab ) {
				$has_tab_actions = TRUE;
			}
		}
        ?>
        <div class="wrap">
			<h1><?php _e('Tools', 'ccbpress-core'); ?></h1>
			<div class="wp-filter">
				<ul class="filter-links">
					<?php foreach ( $all_tabs as $tab ) : ?>
						<li class="<?php echo esc_attr( $tab['tab_id'] ); ?>">
							<a class="<?php echo ( $active_tab === $tab['tab_id'] ? ' current' : '' ); ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'tab' => $tab['tab_id'] ), add_query_arg( array( 'page' => 'ccbpress-tools' ), 'admin.php' ) ) ) ); ?>">
			                    <?php echo $tab['title']; ?>
			                </a>
						</li>
					<?php endforeach; ?>
				</ul>
				<?php if ( $has_tab_actions ) : ?>
					<div class="ccbpress_tab_actions">
					<?php foreach ( $all_tab_actions as $tab_action ) : ?>
						<?php if ( isset( $tab_action['tab_id'] ) && $tab_action['tab_id'] == $active_tab ) : ?>
							<a class="button button-<?php echo $tab_action['type']; ?><?php echo ( is_null( $tab_action['class'] ) ) ? '' : ' ' . $tab_action['class']; ?>" href="<?php echo esc_url( $tab_action['link'] ); ?>"<?php echo ( is_null( $tab_action['target'] ) ) ? '' : ' target="' . $tab_action['target'] . '"'; ?>><?php echo $tab_action['title']; ?></a>
						<?php endif; ?>
					<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
            <div id="ccbpress_tab_container" class="metabox-holder">
				<div class="postbox">
					<div class="inside">
		    			<form method="post" action="options.php">
		    				<table class="form-table">
								<?php
								foreach ( $all_tabs as $tab ) {
									if ( isset( $tab['tab_id'] ) && isset( $tab['settings_id'] ) && $tab['tab_id'] == $active_tab ) {
										settings_fields( $tab['settings_id'] );
										do_settings_sections( $tab['settings_id'] );
										if ( TRUE === $tab['submit'] ) {
											submit_button();
										}
										settings_errors();
									}
								}
								?>
		    				</table>
		    			</form>
					</div>
				</div>
    		</div><!-- #tab_container-->
        </div>
        <?php
    }

	public function settings_page_help() {

		global $ccbpress_settings_help_page;
		$screen = get_current_screen();

		if ( $screen->id != $ccbpress_settings_help_page ) {
			return;
		}

		$all_tabs = apply_filters( 'ccbpress_settings_page_tabs', array() );
		$active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $all_tabs[0]['tab_id'];

		$help_tabs = apply_filters('ccbpress_settings_help_tabs', array() );
		foreach( $help_tabs as $help_tab ) {
			if ( $help_tab['tab_id'] == $active_tab ) {
				$screen->add_help_tab( array(
					'id'		=> $help_tab['tab_id'],
					'title'		=> $help_tab['title'],
					'content'	=> $help_tab['content']
				) );
			}
		}

	}

	public function tools_page_help() {

		global $ccbpress_tools_help_page;
		$screen = get_current_screen();

		if ( $screen->id != $ccbpress_tools_help_page ) {
			return;
		}

		$all_tabs = apply_filters( 'ccbpress_tools_page_tabs', array() );
		$active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $all_tabs[0]['tab_id'];

		$help_tabs = apply_filters('ccbpress_tools_help_tabs', array() );
		foreach( $help_tabs as $help_tab ) {
			if ( $help_tab['tab_id'] == $active_tab ) {
				$screen->add_help_tab( array(
					'id'		=> $help_tab['tab_id'],
					'title'		=> $help_tab['title'],
					'content'	=> $help_tab['content']
				) );
			}
		}

	}

}

new CCBPress_Admin_Pages();
