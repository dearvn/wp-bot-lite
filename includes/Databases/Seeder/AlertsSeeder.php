<?php

namespace Dearvn\BotLite\Databases\Seeder;

use Dearvn\BotLite\Abstracts\DBSeeder;
use Dearvn\BotLite\Common\Keys;

/**
 * Alerts Seeder class.
 *
 * Seed some fresh emails for initial startup.
 */
class AlertsSeeder extends DBSeeder {

    /**
     * Run Alerts seeder.
     *
     * @since 0.3.0
     *
     * @return void
     */
    public function run() {
        global $wpdb;

        // Check if there is already a seeder runs for this plugin.
        $already_seeded = (bool) get_option( Keys::ALERT_SEEDER_RAN, false );
        if ( $already_seeded ) {
            return;
        }

        // Generate some alerts.
        $alerts = array(
            array(
                'name'          => 'Enter Call',
                'type'          => 'Buy Long',
                'ticker'        => 'AAPL',
                'interval'      => 5,
                'close'         => 154.20,
                'exchange'      => 'NASDAQ',
                'created_at'            => current_datetime()->format( 'Y-m-d H:i:s' ),
                
            ),
        );

        // Create each of the alerts.
        foreach ( $alerts as $alert ) {
            $wpdb->insert(
                $wpdb->prefix . 'botlite_alerts',
                $alert
            );
        }

        // Update that seeder already runs.
        update_option( Keys::ALERT_SEEDER_RAN, true );
    }
}
