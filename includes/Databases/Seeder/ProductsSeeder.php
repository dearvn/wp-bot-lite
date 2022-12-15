<?php

namespace Dearvn\LandLite\Databases\Seeder;

use Dearvn\LandLite\Abstracts\DBSeeder;
use Dearvn\LandLite\Common\Keys;

/**
 * Products Seeder class.
 *
 * Seed some fresh emails for initial startup.
 */
class ProductsSeeder extends DBSeeder {

    /**
     * Run Products seeder.
     *
     * @since 0.3.0
     *
     * @return void
     */
    public function run() {
        global $wpdb;

        // Check if there is already a seeder runs for this plugin.
        $already_seeded = (bool) get_option( Keys::PRODUCT_SEEDER_RAN, false );
        if ( $already_seeded ) {
            return;
        }

        // Generate some products.
        $products = [
            [
                'title'       => 'First Product Post',
                'slug'        => 'first-product-post',
                'description' => 'This is a simple product post.',
                'is_active'   => 1,
                'city_id'  => 1,
                'product_type_id' => 1,
                'created_by'  => get_current_user_id(),
                'created_at'  => current_datetime()->format( 'Y-m-d H:i:s' ),
                'updated_at'  => current_datetime()->format( 'Y-m-d H:i:s' ),
            ],
        ];

        // Create each of the products.
        foreach ( $products as $product ) {
            $wpdb->insert(
                $wpdb->prefix . 'landlite_products',
                $product
            );
        }

        // Update that seeder already runs.
        update_option( Keys::PRODUCT_SEEDER_RAN, true );
    }
}
