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
                    'id'    	=> 'ccbpress_purge_all_cache',
                    'title' 	=> __( 'Purge all cache', 'ccbpress' ),
                    'href'  	=> wp_nonce_url( admin_url( 'admin-post.php?action=ccbpress-purge-all-cache' ), 'ccbpress-purge-all-cache' ),
                    'parent'    => 'ccbpress',
            ) );

        }
    }

}
new CCBPress_Admin_Bar();
