<?php

namespace Dearvn\LandLite\Admin;

/**
 * Admin Menu class.
 *
 * Responsible for managing admin menus.
 */
class Menu {

    /**
     * Constructor.
     *
     * @since 0.2.0
     */
    public function __construct() {
        add_action( 'admin_menu', [ $this, 'init_menu' ] );
    }

    /**
     * Init Menu.
     *
     * @since 0.2.0
     *
     * @return void
     */
    public function init_menu() {
        global $submenu;

        $slug          = LAND_LITE_SLUG;
        $menu_position = 50;
        $capability    = 'manage_options';
        $logo_icon     = LAND_LITE_ASSETS . '/images/wp-land-lite-logo.png';

        add_menu_page( esc_attr__( 'WP Land Lite', 'landlite' ), esc_attr__( 'WP Land Lite', 'landlite' ), $capability, $slug, [ $this, 'plugin_page' ], $logo_icon, $menu_position );

        if ( current_user_can( $capability ) ) {
            $submenu[ $slug ][] = [ esc_attr__( 'Home', 'landlite' ), $capability, 'admin.php?page=' . $slug . '#/' ]; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
            $submenu[ $slug ][] = [ esc_attr__( 'Products', 'landlite' ), $capability, 'admin.php?page=' . $slug . '#/products' ]; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
        }
    }

    /**
     * Render the plugin page.
     *
     * @since 0.2.0
     *
     * @return void
     */
    public function plugin_page() {
        require_once LAND_LITE_TEMPLATE_PATH . '/app.php';
    }
}
