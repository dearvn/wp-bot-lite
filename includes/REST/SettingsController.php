<?php

namespace Dearvn\BotLite\REST;

use Dearvn\BotLite\Abstracts\RESTController;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;
use WP_Error;

/**
 * API SettingsController class.
 *
 * @since 1.0.0
 */
class SettingsController extends RESTController {

    /**
     * Route base.
     *
     * @var string
     */
    protected $base = 'settings';

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
                    'callback'            => [ $this, 'get_settings' ],
                    'permission_callback' => [ $this, 'check_permission' ],
                    'schema'              => [ $this, 'get_item_schema' ],
                ],
                [
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => [ $this, 'create_item' ],
                    'permission_callback' => [ $this, 'check_permission' ],
                    'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
                ],
            ]
        );
    }

    /**
     * Retrieves setting items as object.
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     * @since 1.0.0
     */
    public function get_settings( WP_REST_Request $request ): ?WP_REST_Response {
        $settings = wp_bot_lite()->settings->get();
	    return rest_ensure_response( $settings );
    }

    /**
     * Create or update settings.
     *
     * @since 1.0.0
     *
     * @param WP_Rest_Request $request
     *
     * @return WP_REST_Response|WP_Error|array
     */
    public function create_item( $request ) {
        $prepared_data = $this->prepare_item_for_database( $request );

        if ( is_wp_error( $prepared_data ) ) {
            return $prepared_data;
        }

        // Update the settings.
        $settings = wp_bot_lite()->settings->create( $prepared_data );

        if ( is_wp_error( $settings ) ) {
            return $settings;
        }

        return rest_ensure_response( $settings );
    }

    /**
     * Retrieves the group schema, conforming to JSON Schema.
     *
     * @since 1.0.0
     *
     * @return array
     */
    public function get_item_schema(): array {
        if ( $this->schema ) {
            return $this->add_additional_fields_schema( $this->schema );
        }

        $schema = [
            '$schema'    => 'http://json-schema.org/draft-04/schema#',
            'title'      => 'settings',
            'type'       => 'object',
            'properties' => [
                'enable_trading' => [
                    'description' => __( 'Enable Trading', 'botlite' ),
                    'type'        => 'string',
                    'context'     => [ 'view', 'edit' ],
                    'required'    => true,
                    'minLength'   => 1,
                    'enum'        => [
	                    'yes',
	                    'no',
                    ],
                    'arg_options' => [
                        'sanitize_callback' => 'sanitize_text_field',
                    ],
                ],
                'api_key' => [
                    'description' => __( 'Binance API Key', 'botlite' ),
                    'type'        => 'string',
                    'context'     => [ 'view', 'edit' ],
                    'required'    => true,
                    'minLength'   => 1,
                    'arg_options' => [
                        'sanitize_callback' => 'sanitize_text_field',
                    ],
                ],
                'secret_key' => [
                    'description' => __( 'Binance Secret Key', 'botlite' ),
                    'type'        => 'string',
                    'context'     => [ 'view', 'edit' ],
                    'required'    => true,
                    'minLength'   => 1,
                    'arg_options' => [
                        'sanitize_callback' => 'sanitize_text_field',
                    ],
                ],
            ],
        ];

        $this->schema = $schema;

        return $this->add_additional_fields_schema( $this->schema );
    }

    /**
     * Prepares setting for create or update.
     *
     * @since 1.0.0
     *
     * @param WP_REST_Request $request Request object.
     *
     * @return array
     */
    protected function prepare_item_for_database( $request ): array {
        $data = [];
	    $data['enable_trading'] = $request['enable_trading'];
        $data['api_key'] = $request['api_key'];
        $data['secret_key'] = $request['secret_key'];
        return $data;
    }
}
