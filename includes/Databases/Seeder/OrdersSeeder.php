<?php

namespace Dearvn\BotLite\Databases\Seeder;

use Dearvn\BotLite\Abstracts\DBSeeder;
use Dearvn\BotLite\Common\Keys;

/**
 * Orders Seeder class.
 *
 * Seed some fresh emails for initial startup.
 */
class OrdersSeeder extends DBSeeder {

    /**
     * Run Orders seeder.
     *
     * @since 0.3.0
     *
     * @return void
     */
    public function run() {
        global $wpdb;

        // Check if there is already a seeder runs for this plugin.
        $already_seeded = (bool) get_option( Keys::ORDER_SEEDER_RAN, false );
        if ( $already_seeded ) {
            return;
        }

        // Generate some orders.
        $orders = array(
            array(
                'name'          => 'Enter Call',
                'type'          => 'Long',
                'ticker'        => 'AAPL',
                'interval'      => 5,
                'exchange'      => 'NASDAQ',
                'entry_price'         => 154.20,
                'entry_at'            => current_datetime()->format( 'Y-m-d H:i:s' ),
            ),
        );

        // Create each of the orders.
        foreach ( $orders as $order ) {
            $wpdb->insert(
                $wpdb->prefix . 'botlite_orders',
                $order
            );
        }

        // Update that seeder already runs.
        update_option( Keys::ORDER_SEEDER_RAN, true );
    }
}
