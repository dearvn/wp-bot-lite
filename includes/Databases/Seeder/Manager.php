<?php

namespace Dearvn\LandLite\Databases\Seeder;

/**
 * Database Seeder class.
 *
 * It'll seed all of the seeders.
 */
class Manager {

    /**
     * Run the database seeders.
     *
     * @since 0.3.0
     *
     * @return void
     * @throws \Exception
     */
    public function run() {
        $seeder_classes = [
            \Dearvn\LandLite\Databases\Seeder\ProductTypeSeeder::class,
            \Dearvn\LandLite\Databases\Seeder\ProductsSeeder::class,
        ];

        foreach ( $seeder_classes as $seeder_class ) {
            $seeder = new $seeder_class();
            $seeder->run();
        }
    }
}
