<?php
/**
 * Admin Notices class
 *
 * @package CCBPress_Core
 * @since 1.0.0
 */
class CCBPress_Admin_Notices {

    public function __construct() {
        $this->actions();
    }

    private function actions() {
        add_action( 'admin_notices', array( $this, 'group_sync' ) );
    }

    public function group_sync() {

		if ( ! CCBPress()->ccb->is_connected() ) {
			return;
		}

		// Get the array of registered services
		$services = apply_filters( 'ccbpress_ccb_services', array() );
		$last_sync = get_option( 'ccbpress_last_group_sync', 'Never' );

		// Check for group related services
		if ( ( in_array( 'group_profiles', $services ) || in_array( 'group_profile_from_id', $services ) ) && 'Never' === $last_sync ) {
		?>
			<div class="notice notice-warning ccbpress-group-sync-never">
				<p><strong><?php _e('CCBPress Core', 'ccbpress-core'); ?></strong><br /><?php _e('Group services are registered, but data has not yet been imported.', 'ccbpress-core'); ?> <a href="<?php echo admin_url( add_query_arg( array( 'tab' => 'sync' ), add_query_arg( array( 'page' => 'ccbpress-settings' ), 'admin.php' ) ) ); ?>"><?php _e('Please run a manual import now.', 'ccbpress-core'); ?></a></p>
			</div>
		<?php
		}

    }

}
new CCBPress_Admin_Notices();
