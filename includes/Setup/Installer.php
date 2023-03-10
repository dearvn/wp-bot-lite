<?php

namespace Dearvn\BotLite\Setup;

use Dearvn\BotLite\Common\Keys;

/**
 * Class Installer.
 *
 * Install necessary database tables and options for the plugin.
 */
class Installer {

    /**
     * Run the installer.
     *
     * @since 0.3.0
     *
     * @return void
     */
    public function run(): void {
        // Update the installed version.
        $this->add_version();

        // Register and create tables.
        $this->register_table_names();
        $this->create_tables();

        // Make this administrator user as city.
        $this->make_admin_as_city();

        // Run the database seeders.
        $seeder = new \Dearvn\BotLite\Databases\Seeder\Manager();
        $seeder->run();
    }

    /**
     * Make administrator user as city.
     *
     * @since 0.5.0
     *
     * @return void
     */
    private function make_admin_as_city() {
        update_user_meta( get_current_user_id(), 'user_type', 'city' );
    }

    /**
     * Register table names.
     *
     * @since 0.3.0
     *
     * @return void
     */
    private function register_table_names(): void {
        global $wpdb;

        // Register the tables to wpdb global.
        $wpdb->botlite_alert_types = $wpdb->prefix . 'botlite_alert_types';
        $wpdb->botlite_alerts      = $wpdb->prefix . 'botlite_alerts';
        $wpdb->botlite_orders      = $wpdb->prefix . 'botlite_orders';
    }

    /**
     * Add time and version on DB.
     *
     * @since 0.3.0
     * @since 0.4.1 Fixed #11 - Version Naming.
     *
     * @return void
     */
    public function add_version(): void {
        $installed = get_option( Keys::BOT_LITE_INSTALLED );

        if ( ! $installed ) {
            update_option( Keys::BOT_LITE_INSTALLED, time() );
        }

        update_option( Keys::BOT_LITE_VERSION, BOT_LITE_VERSION );
    }

    /**
     * Create necessary database tables.
     *
     * @since BOT_LITE_
     *
     * @return void
     */
    public function create_tables() {
        if ( ! function_exists( 'dbDelta' ) ) {
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        }

        // Run the database table migrations.
        \Dearvn\BotLite\Databases\Migrations\AlertTypeMigration::migrate();
        \Dearvn\BotLite\Databases\Migrations\AlertsMigration::migrate();
        \Dearvn\BotLite\Databases\Migrations\OrdersMigration::migrate();
    }
}
