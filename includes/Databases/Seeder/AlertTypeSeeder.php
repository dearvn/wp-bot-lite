<?php

namespace Dearvn\BotLite\Databases\Seeder;

use Dearvn\BotLite\Abstracts\DBSeeder;
use Dearvn\BotLite\Common\Keys;

/**
 * AlertType Seeder class.
 *
 * Seed some fresh emails for initial startup.
 */
class AlertTypeSeeder extends DBSeeder {

    /**
     * Run Alerts seeder.
     *
     * @since 0.5.0
     *
     * @return void
     */
    public function run() {
        global $wpdb;

        // Check if there is already a seeder runs for this plugin.
        $already_seeded = (bool) get_option( Keys::ALERT_TYPE_SEEDER_RAN, false );
        if ( $already_seeded ) {
            return;
        }

        // Generate some alert_types.
        $alert_types = [
            [
                'name'        => 'Rent',
                'slug'        => 'rent',
                'description' => 'This is a full time alert post.',
                'created_at'  => current_datetime()->format( 'Y-m-d H:i:s' ),
                'updated_at'  => current_datetime()->format( 'Y-m-d H:i:s' ),
            ],
            [
                'name'        => 'Sell',
                'slug'        => 'sell',
                'description' => 'This is a part time alert post.',
                'created_at'  => current_datetime()->format( 'Y-m-d H:i:s' ),
                'updated_at'  => current_datetime()->format( 'Y-m-d H:i:s' ),
            ]
        ];

        // Create each of the alert_types.
        foreach ( $alert_types as $alert_type ) {
            $wpdb->insert(
                $wpdb->prefix . 'botlite_alert_types',
                $alert_type
            );
        }

        // Update that seeder already runs.
        update_option( Keys::ALERT_TYPE_SEEDER_RAN, true );
    }
}
