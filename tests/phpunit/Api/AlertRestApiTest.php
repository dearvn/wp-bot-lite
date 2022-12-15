<?php

namespace Dearvn\BotLite\Tests\Api;

use Dearvn\BotLite\Alerts\Alert;

class AlertRestApiTest extends \WP_UnitTestCase {

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
    protected $namespace = 'bot-lite/v1';

    /**
     * Alert Instance.
     *
     * @var Dearvn\BotLite\Alerts\Alert
     */
    public Alert $alert;

    /**
     * Alert Manager Instance.
     *
     * @var Dearvn\BotLite\Alerts\Manager
     */
    public $alert_manager;

    /**
     * Setup test environment.
     */
    protected function setUp() : void {
        // Initialize REST Server.
        global $wp_rest_server;

        parent::setUp();

        $this->alert = new Alert();

        // Truncate alerts table first before running test-suits.
        $this->alert->truncate();

		$this->server = $wp_rest_server = new \WP_REST_Server;
		do_action( 'rest_api_init' );
    }

    /**
     * @test
     * @group alerts-rest-api
     */
    public function test_alerts_list_endpoint_exists() {
        $endpoint = '/' . $this->namespace . '/alerts';

        $request  = new \WP_REST_Request( 'GET', $endpoint );

        $response = $this->server->dispatch( $request );

        $this->assertEquals( 200, $response->get_status() );
	}

    /**
     * @test
     * @group alerts-rest-api
     */
    public function test_alerts_list_endpoint_returns_array() {
        $endpoint = '/' . $this->namespace . '/alerts';
        $request  = new \WP_REST_Request( 'GET', $endpoint );
        $response = $this->server->dispatch( $request );
        $data     = $response->get_data();
        $this->assertIsArray( $data );
    }

    /**
     * @test
     * @group alerts-rest-api
     */
    public function test_alert_list_endpoint_can_send_total() {
        $endpoint = '/' . $this->namespace . '/alerts';
        $request  = new \WP_REST_Request( 'GET', $endpoint );
        $response = $this->server->dispatch( $request );

        $this->assertEquals( 0, $response->get_headers()['X-WP-Total'] );
        $this->assertEquals( 0, $response->get_headers()['X-WP-TotalPages'] );
    }

    /**
     * @test
     * @group alerts-rest-api
     */
    public function test_can_get_alert_detail() {
        $endpoint = '/' . $this->namespace . '/alerts';
        $request  = new \WP_REST_Request( 'POST', $endpoint );
        $request->set_body_params( [
            'title'       => 'Alert Title',
            'description' => 'Alert Description',
            'city_id'  => 1,
            'alert_type_id' => 2,
            'is_active'   => 1,
        ] );
        $response = $this->server->dispatch( $request );
        $data     = $response->get_data();

        // Hit alert detail api endpoint.
        $endpoint = '/' . $this->namespace . '/alerts/' . $data['id'];
        $request  = new \WP_REST_Request( 'GET', $endpoint );
        $response = $this->server->dispatch( $request );
        $response = $response->get_data();

        // Check if alert detail id found.
        $this->assertEquals( $data['title'], $response['title'] );
    }

    /**
     * @test
     * @group alerts-rest-api
     */
    public function test_alert_endpoint_can_create_alert() {
        $endpoint = '/' . $this->namespace . '/alerts';
        $request  = new \WP_REST_Request( 'POST', $endpoint );
        $request->set_body_params( [
            'title'       => 'Alert Title',
            'description' => 'Alert Description',
            'city_id'  => 1,
            'alert_type_id' => 2,
            'is_active'   => 1,
        ] );
        $response = $this->server->dispatch( $request );
        $data     = $response->get_data();
        $this->assertEquals( 1, $data['id'] );

        // Check total count of alerts.
        $endpoint = '/' . $this->namespace . '/alerts';
        $request  = new \WP_REST_Request( 'GET', $endpoint );
        $response = $this->server->dispatch( $request );
        $data     = $response->get_data();
        $this->assertEquals( 1, count( $data ) );
    }

    /**
     * @test
     * @group alerts-rest-api
     */
    public function test_alert_endpoint_can_not_create_without_title() {
        $endpoint = '/' . $this->namespace . '/alerts';
        $request  = new \WP_REST_Request( 'POST', $endpoint );
        $request->set_body_params( [
            'description' => 'Alert Description',
            'city_id'  => 1,
            'alert_type_id' => 2,
            'is_active'   => 1,
        ] );
        $response = $this->server->dispatch( $request );

        $this->assertEquals( 400, $response->get_status() );
        $this->assertSame( 'rest_missing_callback_param', $response->get_data()['code'] );
    }

    /**
     * @test
     * @group alerts-rest-api
     */
    public function test_can_slug_will_be_auto_generated_if_not_given() {
        $endpoint = '/' . $this->namespace . '/alerts';
        $request  = new \WP_REST_Request( 'POST', $endpoint );
        $request->set_body_params( [
            'title'       => 'Alert Title',
            'description' => 'Alert Description',
            'city_id'  => 1,
            'alert_type_id' => 2,
            'is_active'   => 1,
        ] );

        $response = $this->server->dispatch( $request );
        $data     = $response->get_data();

        $this->assertEquals( 'alert-title', $data['slug'] );
    }

    /**
     * @test
     * @group alerts-rest-api
     */
    public function test_can_create_multiple_alert_without_slug_same_time() {
        $endpoint = '/' . $this->namespace . '/alerts';
        $request  = new \WP_REST_Request( 'POST', $endpoint );
        $request->set_body_params( [
            'title'       => 'Alert Title',
            'description' => 'Alert Description',
            'city_id'  => 1,
            'alert_type_id' => 2,
            'is_active'   => 1,
        ] );
        $response = $this->server->dispatch( $request );
        $data     = $response->get_data();
        $this->assertEquals( 'alert-title', $data['slug'] );

        $request  = new \WP_REST_Request( 'POST', $endpoint );
        $request->set_body_params( [
            'title'       => 'Alert Title',
            'description' => 'Alert Description',
            'city_id'  => 1,
            'alert_type_id' => 2,
            'is_active'   => 1,
        ] );
        $response = $this->server->dispatch( $request );
        $data     = $response->get_data();
        $this->assertEquals( 'alert-title-1', $data['slug'] );

        $request  = new \WP_REST_Request( 'POST', $endpoint );
        $request->set_body_params( [
            'title'       => 'Alert Title',
            'description' => 'Alert Description',
            'city_id'  => 1,
            'alert_type_id' => 2,
            'is_active'   => 1,
        ] );
        $response = $this->server->dispatch( $request );
        $data     = $response->get_data();
        $this->assertEquals( 'alert-title-2', $data['slug'] );

        $request  = new \WP_REST_Request( 'POST', $endpoint );
        $request->set_body_params( [
            'title'       => 'Alert Title',
            'description' => 'Alert Description',
            'city_id'  => 1,
            'alert_type_id' => 2,
            'is_active'   => 1,
        ] );
        $response = $this->server->dispatch( $request );
        $data     = $response->get_data();
        $this->assertEquals( 'alert-title-3', $data['slug'] );
    }

    /**
     * @test
     * @group alerts-rest-api
     */
    public function test_can_update_alert() {
        $endpoint = '/' . $this->namespace . '/alerts';
        $request  = new \WP_REST_Request( 'POST', $endpoint );
        $request->set_body_params( [
            'title'       => 'Alert Title',
            'description' => 'Alert Description',
            'city_id'  => 1,
            'alert_type_id' => 2,
            'is_active'   => 1,
        ] );
        $response = $this->server->dispatch( $request );
        $data     = $response->get_data();
        $this->assertEquals( 'alert-title', $data['slug'] );
        $this->assertEquals( 1, $data['id'] );

        // Update alert.
        $endpoint = '/' . $this->namespace . '/alerts/' . $data['id'];
        $request  = new \WP_REST_Request( 'PUT', $endpoint );
        $request->set_body_params( [
            'title'       => 'Alert Title Updated',
            'description' => 'Alert Description Updated',
            'city_id'  => 1,
            'alert_type_id' => 2,
            'is_active'   => 1,
        ] );

        $response = $this->server->dispatch( $request );
        $data     = $response->get_data();
        $this->assertEquals( 'Alert Title Updated', $data['title'] );
        $this->assertEquals( 'alert-title-updated', $data['slug'] );
    }

    /**
     * @test
     *
     * @return void
     */
    public function test_can_delete_alerts() {
        $endpoint = '/' . $this->namespace . '/alerts';
        $request  = new \WP_REST_Request( 'POST', $endpoint );
        $request->set_body_params( [
            'title'       => 'Alert Title',
            'description' => 'Alert Description',
            'city_id'  => 1,
            'alert_type_id' => 2,
            'is_active'   => 1,
        ] );
        $response = $this->server->dispatch( $request );
        $data     = $response->get_data();

        // Count total alerts
        $endpoint = '/' . $this->namespace . '/alerts';
        $request  = new \WP_REST_Request( 'GET', $endpoint );
        $response = $this->server->dispatch( $request );
        $data     = $response->get_data();
        $this->assertEquals( 1, count( $data ) );

        // Delete Alert
        $endpoint = '/' . $this->namespace . '/alerts';
        $request  = new \WP_REST_Request( 'DELETE', $endpoint );
        $request->set_param( 'ids', [$data[0]['id']] );
        $response = $this->server->dispatch( $request );
        $this->assertEquals( 200, $response->get_status() );

        // Count total alerts
        $endpoint = '/' . $this->namespace . '/alerts';
        $request  = new \WP_REST_Request( 'GET', $endpoint );
        $response = $this->server->dispatch( $request );
        $data     = $response->get_data();
        $this->assertEquals( 0, count( $data ) );
    }
}
