<?php

namespace Dearvn\BotLite\Databases\Migrations;

use Dearvn\BotLite\Abstracts\DBMigrator;

/**
 * Alert type table Migration class.
 */
class AlertTypeMigration extends DBMigrator {

    /**
     * Migrate the alert_types table.
     *
     * @since 0.5.0
     *
     * @return void
     */
    public static function migrate() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $schema_alert_types = "CREATE TABLE IF NOT EXISTS `{$wpdb->botlite_alert_types}` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `slug` varchar(255) NOT NULL,
            `description` varchar(255) NOT NULL,
            `created_at` datetime NOT NULL,
            `updated_at` datetime NOT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `slug` (`slug`)
        ) $charset_collate;";

        // Create the tables.
        dbDelta( $schema_alert_types );
    }
}
