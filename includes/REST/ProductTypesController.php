<?php

namespace Dearvn\LandLite\REST;

use Dearvn\LandLite\Abstracts\RESTController;
use Dearvn\LandLite\Products\ProductType;
use WP_REST_Response;
use WP_REST_Server;
use WP_Error;

/**
 * API ProductTypesController class.
 *
 * @since 0.3.0
 */
class ProductTypesController extends RESTController {

    /**
     * Route base.
     *
     * @var string
     */
    protected $base = 'product-types';

    /**
     * Register all routes related with carts.
     *
     * @return void
     */
    public function register_routes() {
        register_rest_route(
            $this->namespace, '/' . $this->base . '/',
            [
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'get_items' ],
                    'permission_callback' => '__return_true',
                    'args'                => $this->get_collection_params(),
                    'schema'              => [ $this, 'get_item_schema' ],
                ],
            ]
        );
    }

    /**
     * Retrieves a collection of product items.
     *
     * @since 0.3.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     */
    public function get_items( $request ): ?WP_REST_Response {
        $args = [
            'orderby' => 'id',
            'order'   => 'ASC',
        ];

        $params = $this->get_collection_params();

        foreach ( $params as $key => $value ) {
            if ( isset( $request[ $key ] ) ) {
                $args[ $key ] = $request[ $key ];
            }
        }

        $product_type = new ProductType();
        $product_types = $product_type->all( $args );

        foreach ( $product_types as $product_type_single ) {
            $response = $this->prepare_item_for_response( $product_type_single, $request );
            $data[]   = $this->prepare_response_for_collection( $response );
        }

        $args['count'] = 1;
        $total         = $product_type->all( $args );
        $max_pages     = ceil( $total / (int) $args['limit'] );
        $response      = rest_ensure_response( $data );

        $response->header( 'X-WP-Total', (int) $total );
        $response->header( 'X-WP-TotalPages', (int) $max_pages );

        return $response;
    }

    /**
     * Prepares the item for the REST response.
     *
     * @since 0.3.0
     *
     * @param Product            $item    WordPress representation of the item
     * @param WP_REST_Request $request request object
     *
     * @return WP_Error|WP_REST_Response
     */
    public function prepare_item_for_response( $item, $request ): ?WP_REST_Response {
        $data = [];

        $data = ProductType::to_array( $item );

        $data = $this->prepare_response_for_collection( $data );

        $context = ! empty( $request['context'] ) ? $request['context'] : 'view';
        $data    = $this->filter_response_by_context( $data, $context );

        $response = rest_ensure_response( $data );
        $response->add_links( $this->prepare_links( $item ) );

        return $response;
    }

    /**
     * Prepares links for the request.
     *
     * @since 0.3.0
     *
     * @param WP_Post $post post object
     *
     * @return array links for the given data.
     */
    protected function prepare_links( $item ): array {
        $base = sprintf( '%s/%s%s', $this->namespace, $this->rest_base, $this->base );

        $id = is_object( $item ) ? $item->id : $item['id'];

        $links = [
            'self' => [
                'href' => rest_url( trailingslashit( $base ) . $id ),
            ],
            'collection' => [
                'href' => rest_url( $base ),
            ],
        ];

        return $links;
    }

    /**
     * Retrieves the query params for collections.
     *
     * @since 0.3.0
     *
     * @return array
     */
    public function get_collection_params(): array {
        $params = parent::get_collection_params();

        $params['limit']['default'] = 10;
        $params['s']['default']     = '';

        return $params;
    }
}
