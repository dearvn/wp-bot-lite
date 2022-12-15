<?php

namespace Dearvn\LandLite\Tests\Api;

use Dearvn\LandLite\Products\Product;

class ProductRestApiTest extends \WP_UnitTestCase {

    /**
	 * Test REST Server
	 *
	 * @var WP_REST_Server
	 */
	protected $server;

    /**
     * Namespace.
     *
     * @var string
     */
    protected $namespace = 'product-real-estate/v1';

    /**
     * Product Instance.
     *
     * @var Dearvn\LandLite\Products\Product
     */
    public Product $product;

    /**
     * Product Manager Instance.
     *
     * @var Dearvn\LandLite\Products\Manager
     */
    public $product_manager;

    /**
     * Setup test environment.
     */
    protected function setUp() : void {
        // Initialize REST Server.
        global $wp_rest_server;

        parent::setUp();

        $this->product = new Product();

        // Truncate products table first before running test-suits.
        $this->product->truncate();

		$this->server = $wp_rest_server = new \WP_REST_Server;
		do_action( 'rest_api_init' );
    }

    /**
     * @test
     * @group products-rest-api
     */
    public function test_products_list_endpoint_exists() {
        $endpoint = '/' . $this->namespace . '/products';

        $request  = new \WP_REST_Request( 'GET', $endpoint );

        $response = $this->server->dispatch( $request );

        $this->assertEquals( 200, $response->get_status() );
	}

    /**
     * @test
     * @group products-rest-api
     */
    public function test_products_list_endpoint_returns_array() {
        $endpoint = '/' . $this->namespace . '/products';
        $request  = new \WP_REST_Request( 'GET', $endpoint );
        $response = $this->server->dispatch( $request );
        $data     = $response->get_data();
        $this->assertIsArray( $data );
    }

    /**
     * @test
     * @group products-rest-api
     */
    public function test_product_list_endpoint_can_send_total() {
        $endpoint = '/' . $this->namespace . '/products';
        $request  = new \WP_REST_Request( 'GET', $endpoint );
        $response = $this->server->dispatch( $request );

        $this->assertEquals( 0, $response->get_headers()['X-WP-Total'] );
        $this->assertEquals( 0, $response->get_headers()['X-WP-TotalPages'] );
    }

    /**
     * @test
     * @group products-rest-api
     */
    public function test_can_get_product_detail() {
        $endpoint = '/' . $this->namespace . '/products';
        $request  = new \WP_REST_Request( 'POST', $endpoint );
        $request->set_body_params( [
            'title'       => 'Product Title',
            'description' => 'Product Description',
            'city_id'  => 1,
            'product_type_id' => 2,
            'is_active'   => 1,
        ] );
        $response = $this->server->dispatch( $request );
        $data     = $response->get_data();

        // Hit product detail api endpoint.
        $endpoint = '/' . $this->namespace . '/products/' . $data['id'];
        $request  = new \WP_REST_Request( 'GET', $endpoint );
        $response = $this->server->dispatch( $request );
        $response = $response->get_data();

        // Check if product detail id found.
        $this->assertEquals( $data['title'], $response['title'] );
    }

    /**
     * @test
     * @group products-rest-api
     */
    public function test_product_endpoint_can_create_product() {
        $endpoint = '/' . $this->namespace . '/products';
        $request  = new \WP_REST_Request( 'POST', $endpoint );
        $request->set_body_params( [
            'title'       => 'Product Title',
            'description' => 'Product Description',
            'city_id'  => 1,
            'product_type_id' => 2,
            'is_active'   => 1,
        ] );
        $response = $this->server->dispatch( $request );
        $data     = $response->get_data();
        $this->assertEquals( 1, $data['id'] );

        // Check total count of products.
        $endpoint = '/' . $this->namespace . '/products';
        $request  = new \WP_REST_Request( 'GET', $endpoint );
        $response = $this->server->dispatch( $request );
        $data     = $response->get_data();
        $this->assertEquals( 1, count( $data ) );
    }

    /**
     * @test
     * @group products-rest-api
     */
    public function test_product_endpoint_can_not_create_without_title() {
        $endpoint = '/' . $this->namespace . '/products';
        $request  = new \WP_REST_Request( 'POST', $endpoint );
        $request->set_body_params( [
            'description' => 'Product Description',
            'city_id'  => 1,
            'product_type_id' => 2,
            'is_active'   => 1,
        ] );
        $response = $this->server->dispatch( $request );

        $this->assertEquals( 400, $response->get_status() );
        $this->assertSame( 'rest_missing_callback_param', $response->get_data()['code'] );
    }

    /**
     * @test
     * @group products-rest-api
     */
    public function test_can_slug_will_be_auto_generated_if_not_given() {
        $endpoint = '/' . $this->namespace . '/products';
        $request  = new \WP_REST_Request( 'POST', $endpoint );
        $request->set_body_params( [
            'title'       => 'Product Title',
            'description' => 'Product Description',
            'city_id'  => 1,
            'product_type_id' => 2,
            'is_active'   => 1,
        ] );

        $response = $this->server->dispatch( $request );
        $data     = $response->get_data();

        $this->assertEquals( 'product-title', $data['slug'] );
    }

    /**
     * @test
     * @group products-rest-api
     */
    public function test_can_create_multiple_product_without_slug_same_time() {
        $endpoint = '/' . $this->namespace . '/products';
        $request  = new \WP_REST_Request( 'POST', $endpoint );
        $request->set_body_params( [
            'title'       => 'Product Title',
            'description' => 'Product Description',
            'city_id'  => 1,
            'product_type_id' => 2,
            'is_active'   => 1,
        ] );
        $response = $this->server->dispatch( $request );
        $data     = $response->get_data();
        $this->assertEquals( 'product-title', $data['slug'] );

        $request  = new \WP_REST_Request( 'POST', $endpoint );
        $request->set_body_params( [
            'title'       => 'Product Title',
            'description' => 'Product Description',
            'city_id'  => 1,
            'product_type_id' => 2,
            'is_active'   => 1,
        ] );
        $response = $this->server->dispatch( $request );
        $data     = $response->get_data();
        $this->assertEquals( 'product-title-1', $data['slug'] );

        $request  = new \WP_REST_Request( 'POST', $endpoint );
        $request->set_body_params( [
            'title'       => 'Product Title',
            'description' => 'Product Description',
            'city_id'  => 1,
            'product_type_id' => 2,
            'is_active'   => 1,
        ] );
        $response = $this->server->dispatch( $request );
        $data     = $response->get_data();
        $this->assertEquals( 'product-title-2', $data['slug'] );

        $request  = new \WP_REST_Request( 'POST', $endpoint );
        $request->set_body_params( [
            'title'       => 'Product Title',
            'description' => 'Product Description',
            'city_id'  => 1,
            'product_type_id' => 2,
            'is_active'   => 1,
        ] );
        $response = $this->server->dispatch( $request );
        $data     = $response->get_data();
        $this->assertEquals( 'product-title-3', $data['slug'] );
    }

    /**
     * @test
     * @group products-rest-api
     */
    public function test_can_update_product() {
        $endpoint = '/' . $this->namespace . '/products';
        $request  = new \WP_REST_Request( 'POST', $endpoint );
        $request->set_body_params( [
            'title'       => 'Product Title',
            'description' => 'Product Description',
            'city_id'  => 1,
            'product_type_id' => 2,
            'is_active'   => 1,
        ] );
        $response = $this->server->dispatch( $request );
        $data     = $response->get_data();
        $this->assertEquals( 'product-title', $data['slug'] );
        $this->assertEquals( 1, $data['id'] );

        // Update product.
        $endpoint = '/' . $this->namespace . '/products/' . $data['id'];
        $request  = new \WP_REST_Request( 'PUT', $endpoint );
        $request->set_body_params( [
            'title'       => 'Product Title Updated',
            'description' => 'Product Description Updated',
            'city_id'  => 1,
            'product_type_id' => 2,
            'is_active'   => 1,
        ] );

        $response = $this->server->dispatch( $request );
        $data     = $response->get_data();
        $this->assertEquals( 'Product Title Updated', $data['title'] );
        $this->assertEquals( 'product-title-updated', $data['slug'] );
    }

    /**
     * @test
     *
     * @return void
     */
    public function test_can_delete_products() {
        $endpoint = '/' . $this->namespace . '/products';
        $request  = new \WP_REST_Request( 'POST', $endpoint );
        $request->set_body_params( [
            'title'       => 'Product Title',
            'description' => 'Product Description',
            'city_id'  => 1,
            'product_type_id' => 2,
            'is_active'   => 1,
        ] );
        $response = $this->server->dispatch( $request );
        $data     = $response->get_data();

        // Count total products
        $endpoint = '/' . $this->namespace . '/products';
        $request  = new \WP_REST_Request( 'GET', $endpoint );
        $response = $this->server->dispatch( $request );
        $data     = $response->get_data();
        $this->assertEquals( 1, count( $data ) );

        // Delete Product
        $endpoint = '/' . $this->namespace . '/products';
        $request  = new \WP_REST_Request( 'DELETE', $endpoint );
        $request->set_param( 'ids', [$data[0]['id']] );
        $response = $this->server->dispatch( $request );
        $this->assertEquals( 200, $response->get_status() );

        // Count total products
        $endpoint = '/' . $this->namespace . '/products';
        $request  = new \WP_REST_Request( 'GET', $endpoint );
        $response = $this->server->dispatch( $request );
        $data     = $response->get_data();
        $this->assertEquals( 0, count( $data ) );
    }
}
