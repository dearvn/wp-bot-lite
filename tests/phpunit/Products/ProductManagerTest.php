<?php

namespace Dearvn\LandLite\Tests;

use Dearvn\LandLite\Products\Product;

class ProductManagerTest extends \WP_UnitTestCase {

    /**
     * Product Instance.
     *
     * @var Product
     */
    public Product $product;

    /**
     * Product Manager Instance.
     *
     * @var \Dearvn\LandLite\Products\Manager
     */
    public $product_manager;

    /**
     * Setup test environment.
     */
    protected function setUp() : void {
        parent::setUp();

        $this->product = new Product();
        $this->product_manager = wp_land_lite()->products;

        // Truncate products table first before running tests.
        $this->product->truncate();
    }

    /**
     * @test
     * @group products
     */
    public function test_if_product_count_is_int() {
        $products_count = $this->product_manager->all( [ 'count' => true ] );

        // Check if products_count is an integer.
        $this->assertIsInt( $products_count );
    }

    /**
     * @test
     * @group products
     */
    public function test_if_product_lists_is_array() {
        $products = $this->product_manager->all();
        $this->assertIsArray( $products );
    }

    /**
     * @test
     * @group products
     */
    public function test_can_create_a_product() {
        // Get total products before creating product.
        $products_count = $this->product_manager->all( [ 'count' => true ] );
        $this->assertEquals( 0, $products_count );

        $product_id = $this->product_manager->create( [
            'title'       => 'Product Title',
            'description' => 'Product Description',
            'city_id'  => 1,
            'product_type_id' => 2,
            'is_active'   => 1,
        ] );

        // Check again the total products = 1
        $products_count = $this->product_manager->all( [ 'count' => true ] );
        $this->assertEquals( 1, $products_count );

        // Check if product_id is an integer also.
        $this->assertIsInt( $product_id );
    }

    /**
     * @test
     * @group products
     */
    public function test_can_find_a_product() {
        $product_id = $this->product_manager->create( [
            'title'       => 'Product Title',
            'description' => 'Product Description',
            'city_id'  => 1,
            'product_type_id' => 2,
            'is_active'   => 1,
        ] );
        $this->assertIsInt( $product_id );

        // Find the product
        $product = $this->product_manager->get( [ 'key' => 'id', 'value' => $product_id ] );

        // Check if product is an object
        $this->assertIsObject( $product );

        // Check if product id is found on $product->id
        $this->assertEquals( $product_id, $product->id );
    }

    /**
     * @test
     * @group products
     */
    public function test_can_update_a_product() {
        $product_id = $this->product_manager->create( [
            'title'       => 'Product Title',
            'description' => 'Product Description',
            'city_id'  => 1,
            'product_type_id' => 2,
            'is_active'   => 1,
        ] );
        $this->assertIsInt( $product_id );
        $this->assertGreaterThan( 0, $product_id );
        $this->assertEquals( 1, $this->product_manager->update([
            'title'       => 'Product Title Updated',
            'description' => 'Product Description Updated',
            'city_id'  => 1,
            'product_type_id' => 2,
            'is_active'   => 1,
        ], $product_id));
    }

    /**
     * @test
     * @group products
     */
    public function test_can_delete_a_product() {
        $product_id = $this->product_manager->create( [
            'title'       => 'Product Title',
            'description' => 'Product Description',
            'city_id'  => 1,
            'product_type_id' => 2,
            'is_active'   => 1,
        ] );

        // Check total products = 1
        $products_count = $this->product_manager->all( [ 'count' => true ] );
        $this->assertEquals( 1, $products_count );

        // Delete the product
        $this->product_manager->delete( $product_id );

        // Check total products = 0
        $products_count = $this->product_manager->all( [ 'count' => true ] );
        $this->assertEquals( 0, $products_count );
    }
}
