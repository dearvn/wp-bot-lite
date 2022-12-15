<?php

namespace Dearvn\LandLite\Databases\Seeder;

use Dearvn\LandLite\Abstracts\DBSeeder;
use Dearvn\LandLite\Common\Keys;

/**
 * ProductType Seeder class.
 *
 * Seed some fresh emails for initial startup.
 */
class ProductTypeSeeder extends DBSeeder {

    /**
     * Run Products seeder.
     *
     * @since 0.5.0
     *
     * @return void
     */
    public function run() {
        global $wpdb;

        // Check if there is already a seeder runs for this plugin.
        $already_seeded = (bool) get_option( Keys::PRODUCT_TYPE_SEEDER_RAN, false );
        if ( $already_seeded ) {
            return;
        }

        // Generate some product_types.
        $product_types = [
            [
                'name'        => 'Rent',
                'slug'        => 'rent',
                'description' => 'This is a full time product post.',
                'created_at'  => current_datetime()->format( 'Y-m-d H:i:s' ),
                'updated_at'  => current_datetime()->format( 'Y-m-d H:i:s' ),
            ],
            [
                'name'        => 'Sell',
                'slug'        => 'sell',
                'description' => 'This is a part time product post.',
                'created_at'  => current_datetime()->format( 'Y-m-d H:i:s' ),
                'updated_at'  => current_datetime()->format( 'Y-m-d H:i:s' ),
            ]
        ];

        // Create each of the product_types.
        foreach ( $product_types as $product_type ) {
            $wpdb->insert(
                $wpdb->prefix . 'landlite_product_types',
                $product_type
            );
        }

        // Update that seeder already runs.
        update_option( Keys::PRODUCT_TYPE_SEEDER_RAN, true );
    }
}
