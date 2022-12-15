<?php

namespace Dearvn\BotLite\Tests;

use Dearvn\BotLite\Alerts\Alert;

class AlertManagerTest extends \WP_UnitTestCase {

    /**
     * Alert Instance.
     *
     * @var Alert
     */
    public Alert $alert;

    /**
     * Alert Manager Instance.
     *
     * @var \Dearvn\BotLite\Alerts\Manager
     */
    public $alert_manager;

    /**
     * Setup test environment.
     */
    protected function setUp() : void {
        parent::setUp();

        $this->alert = new Alert();
        $this->alert_manager = wp_bot_lite()->alerts;

        // Truncate alerts table first before running tests.
        $this->alert->truncate();
    }

    /**
     * @test
     * @group alerts
     */
    public function test_if_alert_count_is_int() {
        $alerts_count = $this->alert_manager->all( [ 'count' => true ] );

        // Check if alerts_count is an integer.
        $this->assertIsInt( $alerts_count );
    }

    /**
     * @test
     * @group alerts
     */
    public function test_if_alert_lists_is_array() {
        $alerts = $this->alert_manager->all();
        $this->assertIsArray( $alerts );
    }

    /**
     * @test
     * @group alerts
     */
    public function test_can_create_a_alert() {
        // Get total alerts before creating alert.
        $alerts_count = $this->alert_manager->all( [ 'count' => true ] );
        $this->assertEquals( 0, $alerts_count );

        $alert_id = $this->alert_manager->create( [
            'title'       => 'Alert Title',
            'description' => 'Alert Description',
            'city_id'  => 1,
            'alert_type_id' => 2,
            'is_active'   => 1,
        ] );

        // Check again the total alerts = 1
        $alerts_count = $this->alert_manager->all( [ 'count' => true ] );
        $this->assertEquals( 1, $alerts_count );

        // Check if alert_id is an integer also.
        $this->assertIsInt( $alert_id );
    }

    /**
     * @test
     * @group alerts
     */
    public function test_can_find_a_alert() {
        $alert_id = $this->alert_manager->create( [
            'title'       => 'Alert Title',
            'description' => 'Alert Description',
            'city_id'  => 1,
            'alert_type_id' => 2,
            'is_active'   => 1,
        ] );
        $this->assertIsInt( $alert_id );

        // Find the alert
        $alert = $this->alert_manager->get( [ 'key' => 'id', 'value' => $alert_id ] );

        // Check if alert is an object
        $this->assertIsObject( $alert );

        // Check if alert id is found on $alert->id
        $this->assertEquals( $alert_id, $alert->id );
    }

    /**
     * @test
     * @group alerts
     */
    public function test_can_update_a_alert() {
        $alert_id = $this->alert_manager->create( [
            'title'       => 'Alert Title',
            'description' => 'Alert Description',
            'city_id'  => 1,
            'alert_type_id' => 2,
            'is_active'   => 1,
        ] );
        $this->assertIsInt( $alert_id );
        $this->assertGreaterThan( 0, $alert_id );
        $this->assertEquals( 1, $this->alert_manager->update([
            'title'       => 'Alert Title Updated',
            'description' => 'Alert Description Updated',
            'city_id'  => 1,
            'alert_type_id' => 2,
            'is_active'   => 1,
        ], $alert_id));
    }

    /**
     * @test
     * @group alerts
     */
    public function test_can_delete_a_alert() {
        $alert_id = $this->alert_manager->create( [
            'title'       => 'Alert Title',
            'description' => 'Alert Description',
            'city_id'  => 1,
            'alert_type_id' => 2,
            'is_active'   => 1,
        ] );

        // Check total alerts = 1
        $alerts_count = $this->alert_manager->all( [ 'count' => true ] );
        $this->assertEquals( 1, $alerts_count );

        // Delete the alert
        $this->alert_manager->delete( $alert_id );

        // Check total alerts = 0
        $alerts_count = $this->alert_manager->all( [ 'count' => true ] );
        $this->assertEquals( 0, $alerts_count );
    }
}
