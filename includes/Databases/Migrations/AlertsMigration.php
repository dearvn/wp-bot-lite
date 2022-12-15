<?php

namespace Dearvn\BotLite\Databases\Migrations;

use Dearvn\BotLite\Abstracts\DBMigrator;

/**
 * Alerts migration.
 */
class AlertsMigration extends DBMigrator {

    /**
     * Migrate the alerts table.
     *
     * @since 0.3.0
     *
     * @return void
     */
    public static function migrate() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $schema_alerts = "CREATE TABLE IF NOT EXISTS `{$wpdb->botlite_alerts}` (
            `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `ticker` varchar(255) NOT NULL,
            `type` varchar(255) NOT NULL,
            `exchange` varchar(255) NOT NULL,
            `interval` tinyint(1) unsigned NOT NULL,
            `close` decimal(8,2) NOT NULL,
            `created_at` datetime NOT NULL,
            PRIMARY KEY (`id`)
        ) $charset_collate";

        // Create the tables.
        dbDelta( $schema_alerts );
    }
}
