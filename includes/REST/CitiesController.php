<?php

namespace Dearvn\LandLite\REST;

use Dearvn\LandLite\Abstracts\RESTController;
use WP_User_Query;
use WP_REST_Response;
use WP_REST_Server;
use WP_Error;

/**
 * API CitiesController class.
 *
 * @since 0.5.0
 */
class CitiesController extends RESTController {

    /**
     * Endpoint namespace.
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
     * Register all routes related with carts.
     *
     * @return void
     */
    public function register_routes() {
        register_rest_route(
            $this->namespace, '/' . $this->base . '/dropdown//',
            [
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'get_items_dropdown' ],
                    'permission_callback' => [ $this, 'check_permission' ],
                ],
            ]
        );
    }

    /**
     * Retrieves a collection of cities for dropdown.
     *
     * @since 0.5.0
     *
     * @param WP_REST_Request $request   Full details about the request.
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     */
    public function get_items_dropdown( $request ): ?WP_REST_Response {
        
        $cities = [
            ['name' => 'Los Angeles', 'city_id' => '1'],
            ['name' => 'San Diego', 'city_id' => '2'],
            ['name' => 'San Jose', 'city_id' => '3']
        ];

        

        return rest_ensure_response( $cities );
    }

    /**
     * Prepare dropdown response for collection.
     *
     * @since 0.5.0
     *
     * @param WP_City         $item    City object.
	 * @param WP_REST_Request $request Request object.
     *
     * @return array
     */
    public function prepare_dropdown_response_for_collection( $item, $request ) {
        $data             = [];
        $data['id']       = $item->id;
        $data['name']     = $item->name;
        $data['code']    = $item->code;

        return $data;
    }
}
