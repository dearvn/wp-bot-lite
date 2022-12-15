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
        $alerts = [
            [
                'title'       => 'First Alert Post',
                'slug'        => 'first-alert-post',
                'description' => 'This is a simple alert post.',
                'is_active'   => 1,
                'city_id'  => 1,
                'alert_type_id' => 1,
                'created_by'  => get_current_user_id(),
                'created_at'  => current_datetime()->format( 'Y-m-d H:i:s' ),
                'updated_at'  => current_datetime()->format( 'Y-m-d H:i:s' ),
            ],
        ];

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
