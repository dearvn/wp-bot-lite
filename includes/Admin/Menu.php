<?php

namespace Dearvn\BotLite\Admin;

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

        $slug          = BOT_LITE_SLUG;
        $menu_position = 50;
        $capability    = 'manage_options';
        $logo_icon     = BOT_LITE_ASSETS . '/images/wp-bot-lite-logo.png';

        add_menu_page( esc_attr__( 'WP Bot Lite', 'botlite' ), esc_attr__( 'WP Bot Lite', 'botlite' ), $capability, $slug, [ $this, 'plugin_page' ], $logo_icon, $menu_position );

        if ( current_user_can( $capability ) ) {
            $submenu[ $slug ][] = [ esc_attr__( 'Home', 'botlite' ), $capability, 'admin.php?page=' . $slug . '#/' ]; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
            $submenu[ $slug ][] = [ esc_attr__( 'Settings', 'botlite' ), $capability, 'admin.php?page=' . $slug . '#/settings' ]; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
            $submenu[ $slug ][] = [ esc_attr__( 'Alerts', 'botlite' ), $capability, 'admin.php?page=' . $slug . '#/alerts' ]; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
            $submenu[ $slug ][] = [ esc_attr__( 'Orders', 'botlite' ), $capability, 'admin.php?page=' . $slug . '#/orders' ]; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
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
        require_once BOT_LITE_TEMPLATE_PATH . '/app.php';
    }
}
