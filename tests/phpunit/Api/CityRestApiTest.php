<?php

namespace Dearvn\LandLite\Tests\Api;

class CityRestApiTest extends \WP_UnitTestCase {

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
     * Route base.
     *
     * @var string
     */
    protected $base = 'cities';

    /**
     * Setup test environment.
     */
    protected function setUp() : void {
        // Initialize REST Server.
        global $wp_rest_server;

        parent::setUp();

		$this->server = $wp_rest_server = new \WP_REST_Server;
		do_action( 'rest_api_init' );
    }

    /**
     * @test
     * @group city-rest-api
     */
    public function test_city_dropdown_list_endpoint_exists() {
        $endpoint = '/' . $this->namespace . '/' . $this->base . '/dropdown';

        $request  = new \WP_REST_Request( 'GET', $endpoint );

        $response = $this->server->dispatch( $request );

        $this->assertEquals( 200, $response->get_status() );
	}

    /**
     * @test
     * @group city-rest-api
     */
    public function test_city_dropdown_list_endpoint() {
        $endpoint = '/' . $this->namespace . '/' . $this->base . '/dropdown';
        $request  = new \WP_REST_Request( 'GET', $endpoint );
        $response = $this->server->dispatch( $request );
        $data     = $response->get_data();

        // It must be an array.
        $this->assertTrue( is_array( $data ) );
        $this->assertEquals( count( $data ), 0 );

        // Set city meta to administrator user.
        $user = get_user_by( 'id', 1 );
        if ( $user ) {
            update_user_meta( 1, 'user_type', 'city' );

            $response = $this->server->dispatch( $request );
            $data     = $response->get_data();

            // Length must be 1
            $this->assertEquals( count( $data ), 1 );
        }
    }
}
