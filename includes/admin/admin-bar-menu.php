<?php
/**
 * Admin Bar class
 *
 * @package CCBPress_Core
 * @since 1.0.0
 */
class CCBPress_Admin_Bar {

    public function __construct() {
        $this->actions();
    }

    private function actions() {
        add_action( 'admin_bar_menu', array( $this, 'admin_bar_menu' ), 100 );
    }

    public function admin_bar_menu( $wp_admin_bar ) {
        if ( current_user_can( 'manage_options' ) ) {

            $wp_admin_bar->add_node(
                array(
                    'id'    => 'ccbpress',
                    'title' => '<span class="ab-icon"></span><span class="ab-label">' . __( 'CCBPress', 'ccbpress' ) . '</span>',
                    'href'  => admin_url( 'admin.php?page=ccbpress_welcome' ),
            ) );

			$wp_admin_bar->add_node(
				array(
					'id'     => 'ccbpress-purge-cache',
					'title'  => sprintf( '<span class="dashicons dashicons-trash" style="font: 400 18px/1 dashicons !important; vertical-align: text-top; -webkit-font-smoothing: antialiased !important;"></span> %1s', __( 'Purge cache', 'ccbpress-core' ) ),
					'parent' => 'ccbpress',
			) );

			$wp_admin_bar->add_group(
				array(
					'id' => 'ccbpress-purge-cache-options',
					'parent' => 'ccbpress-purge-cache',
					'meta' => array(
						'class' => 'ab-sub-secondary',
					),
				)
			);

			$wp_admin_bar->add_node(
                array(
                    'id'    	=> 'ccbpress_purge_image_cache',
                    'title' 	=> sprintf( '<span class="dashicons dashicons-images-alt2" style="font: 400 18px/1 dashicons !important; vertical-align: text-top; -webkit-font-smoothing: antialiased !important;"></span> %1s', __( 'Purge image cache', 'ccbpress-core' ) ),
                    'href'  	=> wp_nonce_url( admin_url( 'admin-post.php?action=ccbpress-purge-image-cache' ), 'ccbpress-purge-image-cache' ),
                    'parent'    => 'ccbpress-purge-cache-options',
            ) );

			$wp_admin_bar->add_node(
                array(
                    'id'    	=> 'ccbpress_purge_db_cache',
                    'title' 	=> sprintf( '<span class="dashicons dashicons-list-view" style="font: 400 18px/1 dashicons !important; vertical-align: text-top; -webkit-font-smoothing: antialiased !important;"></span> %1s', __( 'Purge database cache', 'ccbpress-core' ) ),
                    'href'  	=> wp_nonce_url( admin_url( 'admin-post.php?action=ccbpress-purge-db-cache' ), 'ccbpress-purge-db-cache' ),
                    'parent'    => 'ccbpress-purge-cache-options',
            ) );

			$wp_admin_bar->add_node(
                array(
                    'id'    	=> 'ccbpress_purge_transient_cache',
                    'title' 	=> sprintf( '<span class="dashicons dashicons-clock" style="font: 400 18px/1 dashicons !important; vertical-align: text-top; -webkit-font-smoothing: antialiased !important;"></span> %1s', __( 'Purge transient cache', 'ccbpress-core' ) ),
                    'href'  	=> wp_nonce_url( admin_url( 'admin-post.php?action=ccbpress-purge-db-cache' ), 'ccbpress-purge-transient-cache' ),
                    'parent'    => 'ccbpress-purge-cache-options',
            ) );

            $wp_admin_bar->add_node(
                array(
                    'id'    	=> 'ccbpress_purge_all_cache',
                    'title' 	=> sprintf( '<span class="dashicons dashicons-trash" style="font: 400 18px/1 dashicons !important; vertical-align: text-top; -webkit-font-smoothing: antialiased !important;"></span> %1s', __( 'Purge all cache', 'ccbpress-core' ) ),
                    'href'  	=> wp_nonce_url( admin_url( 'admin-post.php?action=ccbpress-purge-all-cache' ), 'ccbpress-purge-all-cache' ),
                    'parent'    => 'ccbpress-purge-cache-options',
            ) );

			// Get the array of registered services
			$services = apply_filters( 'ccbpress_ccb_services', array() );
			// Check for group related services
			if ( ( array_intersect( array( 'event_profiles', 'event_profile', 'group_profiles', 'group_profile_from_id' ), $services ) && false === get_option( 'ccbpress_last_import', false ) ) ) {
				//Add the main siteadmin menu item
				$wp_admin_bar->add_menu( array(
					'id'     => 'ccbpress-import-notice',
					'href'   => admin_url() . 'admin.php?page=ccbpress-settings&tab=import',
					'parent' => 'top-secondary',
					'title'  => esc_html__( 'CCB Data Not Imported', 'ccbpress-core' ),
					'meta'   => array( 'class' => 'ccbpress-import-notice' ),
				) );
			}

        }
    }

}
new CCBPress_Admin_Bar();
