<?php

namespace Dearvn\BotLite\Databases\Migrations;

use Dearvn\BotLite\Abstracts\DBMigrator;

/**
 * Orders migration.
 */
class OrdersMigration extends DBMigrator {

    /**
     * Migrate the orders table.
     *
     * @since 0.3.0
     *
     * @return void
     */
    public static function migrate() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $schema_orders = "CREATE TABLE IF NOT EXISTS `{$wpdb->botlite_orders}` (
            `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `order_id` varchar(255) NULL,
            `ticker` varchar(255) NOT NULL,
            `signal` varchar(255) NULL,
            `logic` varchar(255) NULL,
            `contracts` integer(11) NULL,
            `type` varchar(255) NOT NULL,
            `exchange` varchar(255) NOT NULL,
            `interval` tinyint(1) unsigned NOT NULL,
            `entry_price` decimal(8,2) NOT NULL,
            `entry_at` datetime NOT NULL,
            `exit_price` decimal(8,2) NOT NULL,
            `exit_at` datetime NOT NULL,
            PRIMARY KEY (`id`)
        ) $charset_collate";

        // Create the tables.
        dbDelta( $schema_orders );
    }
}
