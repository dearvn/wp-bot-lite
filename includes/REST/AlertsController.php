<?php

namespace Dearvn\BotLite\REST;

use Dearvn\BotLite\Abstracts\RESTController;
use Dearvn\BotLite\Alerts\Alert;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;
use WP_Error;

/**
 * API AlertsController class.
 *
 * @since 0.3.0
 */
class AlertsController extends RESTController {

    /**
     * Route base.
     *
     * @var string
     */
    protected $base = 'alerts';

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
                    'permission_callback' => [ $this, 'check_permission' ],
                    'args'                => $this->get_collection_params(),
                    'schema'              => [ $this, 'get_item_schema' ],
                ],
                [
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => [ $this, 'create_item' ],
                    'permission_callback' => [ $this, 'check_permission' ],
                    'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
                ],
                [
                    'methods'             => WP_REST_Server::DELETABLE,
                    'callback'            => [ $this, 'delete_items' ],
                    'permission_callback' => [ $this, 'check_permission' ],
                    'args'                => [
                        'ids' => [
                            'type'        => 'array',
                            'default'     => [],
                            'description' => __( 'Post IDs which will be deleted.', 'botlite' ),
                        ],
                    ],
                ],
            ]
        );

        register_rest_route(
            $this->namespace, '/' . $this->base . '/(?P<id>[a-zA-Z0-9-]+)',
            [
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'get_item' ],
                    'permission_callback' => [ $this, 'check_permission' ],
                    'args'                => $this->get_collection_params(),
                ],
                [
                    'methods'             => WP_REST_Server::EDITABLE,
                    'callback'            => [ $this, 'update_item' ],
                    'permission_callback' => [ $this, 'check_permission' ],
                    'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ),
                ],
            ]
        );
    }

    /**
     * Retrieves a collection of alert items.
     *
     * @since 0.3.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     */
    public function get_items( $request ): ?WP_REST_Response {
        $args   = [];
        $data   = [];
        $params = $this->get_collection_params();

        foreach ( $params as $key => $value ) {
            if ( isset( $request[ $key ] ) ) {
                $args[ $key ] = $request[ $key ];
            }
        }

        $alerts = wp_bot_lite()->alerts->all( $args );
        foreach ( $alerts as $alert ) {
            $response = $this->prepare_item_for_response( $alert, $request );
            $data[]   = $this->prepare_response_for_collection( $response );
        }

        $args['count'] = 1;
        $total         = wp_bot_lite()->alerts->all( $args );
        $max_pages     = ceil( $total / (int) $args['limit'] );
        $response      = rest_ensure_response( $data );

        $response->header( 'X-WP-Total', (int) $total );
        $response->header( 'X-WP-TotalPages', (int) $max_pages );

        return $response;
    }

    /**
     * Retrieves a collection of alert items.
     *
     * @since 0.3.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     */
    public function get_item( $request ) {
        if ( is_numeric( $request['id'] ) ) {
            $args = [
                'key'   => 'id',
                'value' => absint( $request['id'] ),
            ];
        } else {
            $args = [
                'key'   => 'slug',
                'value' => sanitize_text_field( wp_unslash( $request['id'] ) ),
            ];
        }

        $alert = wp_bot_lite()->alerts->get( $args );

        if ( ! $alert ) {
            return new WP_Error( 'bot_lite_rest_alert_not_found', __( 'Alert not found. May be alert has been deleted or you don\'t have access to that.', 'botlite' ), [ 'status' => 404 ] );
        }

        // Prepare response.
        $alert = $this->prepare_item_for_response( $alert, $request );

        return rest_ensure_response( $alert );
    }

    /**
     * Create new alert.
     *
     * @since 0.3.0
     *
     * @param WP_Rest_Request $request
     *
     * @return WP_REST_Response|WP_Error
     */
    public function create_item( $request ) {
        if ( ! empty( $request['id'] ) ) {
            return new WP_Error(
                'botlite_rest_email_template_exists',
                __( 'Cannot create existing email template.', 'botlite' ),
                array( 'status' => 400 )
            );
        }

        $prepared_data = $this->prepare_item_for_database( $request );

        if ( is_wp_error( $prepared_data ) ) {
            return $prepared_data;
        }

        // Insert the alert.
        $alert_id = wp_bot_lite()->alerts->create( $prepared_data );

        if ( is_wp_error( $alert_id ) ) {
            return $alert_id;
        }

        // Get alert after insert to sending response.
        $alert = wp_bot_lite()->alerts->get(
            [
				'key' => 'id',
				'value' => $alert_id,
			]
        );

        $response = $this->prepare_item_for_response( $alert, $request );
        $response = rest_ensure_response( $response );

        $response->set_status( 201 );
        $response->header( 'Location', rest_url( sprintf( '%s/%s/%d', $this->namespace, $this->rest_base, $alert_id ) ) );

        return $response;
    }

    /**
     * Update a alert.
     *
     * @since 0.3.0
     *
     * @param WP_Rest_Request $request
     *
     * @return WP_REST_Response|WP_Error
     */
    public function update_item( $request ) {
        if ( empty( $request['id'] ) ) {
            return new WP_Error(
                'botlite_rest_email_template_exists',
                __( 'Invalid Alert ID.', 'botlite' ),
                array( 'status' => 400 )
            );
        }

        $prepared_data = $this->prepare_item_for_database( $request );

        if ( is_wp_error( $prepared_data ) ) {
            return $prepared_data;
        }

        // Update the alert.
        $alert_id = absint( $request['id'] );
        $alert_id = wp_bot_lite()->alerts->update( $prepared_data, $alert_id );

        if ( is_wp_error( $alert_id ) ) {
            return $alert_id;
        }

        // Get alert after insert to sending response.
        $alert = wp_bot_lite()->alerts->get(
            [
				'key' => 'id',
				'value' => $alert_id,
			]
        );

        $response = $this->prepare_item_for_response( $alert, $request );
        $response = rest_ensure_response( $response );

        $response->set_status( 201 );
        $response->header( 'Location', rest_url( sprintf( '%s/%s/%d', $this->namespace, $this->rest_base, $alert_id ) ) );

        return $response;
    }

    /**
     * Delete single or multiple alerts.
     *
     * @since 0.3.0
     *
     * @param array $request
     *
     * @return WP_REST_Response|WP_Error
     */
    public function delete_items( $request ) {
        if ( ! isset( $request['ids'] ) ) {
            return new WP_Error( 'no_ids', __( 'No alert ids found.', 'botlite' ), [ 'status' => 400 ] );
        }

        $deleted = wp_bot_lite()->alerts->delete( $request['ids'] );

        if ( $deleted ) {
            $message = __( 'Alerts deleted successfully.', 'botlite' );

            return rest_ensure_response(
                [
					'message' => $message,
					'total' => $deleted,
				]
            );
        }

        return new WP_Error( 'no_alert_deleted', __( 'No alert deleted. Alert has already been deleted. Please try again.', 'botlite' ), [ 'status' => 400 ] );
    }

    /**
     * Retrieves the group schema, conforming to JSON Schema.
     *
     * @since 0.3.0
     *
     * @return array
     */
    public function get_item_schema() {
        if ( $this->schema ) {
            return $this->add_additional_fields_schema( $this->schema );
        }

        $schema = [
            '$schema'    => 'http://json-schema.org/draft-04/schema#',
            'title'      => 'alert',
            'type'       => 'object',
            'properties' => [
                'id' => [
                    'description' => __( 'ID of the alert', 'botlite' ),
                    'type'        => 'integer',
                    'context'     => [ 'view', 'edit' ],
                    'readonly'    => true,
                ],
                'title' => [
                    'description' => __( 'Alert title', 'botlite' ),
                    'type'        => 'string',
                    'context'     => [ 'view', 'edit' ],
                    'required'    => true,
                    'minLength'   => 1,
                    'arg_options' => [
                        'sanitize_callback' => 'sanitize_text_field',
                    ],
                ],
                'slug' => [
                    'description' => __( 'Alert slug', 'botlite' ),
                    'type'        => 'string',
                    'context'     => [ 'view', 'edit' ],
                    'minLength'   => 1,
                    'arg_options' => [
                        'sanitize_callback' => [ $this, 'sanitize_alert_slug' ],
                    ],
                ],
                'description' => [
                    'description' => __( 'Alert description', 'botlite' ),
                    'type'        => 'string',
                    'context'     => [ 'view', 'edit' ],
                    'required'    => true,
                    'minLength'   => 1,
                ],
                'alert_type_id' => [
                    'description' => __( 'Alert type', 'botlite' ),
                    'type'        => 'integer',
                    'context'     => [ 'view', 'edit' ],
                    'required'    => true,
                    'arg_options' => [
                        'sanitize_callback' => 'absint',
                    ],
                ],
                'city_id' => [
                    'description' => __( 'City', 'botlite' ),
                    'type'        => 'integer',
                    'context'     => [ 'view', 'edit' ],
                    'required'    => true,
                    'arg_options' => [
                        'sanitize_callback' => 'absint',
                    ],
                ],
                'is_active' => [
                    'description' => __( 'Alert status', 'botlite' ),
                    'type'        => 'boolean',
                    'context'     => [ 'view', 'edit' ],
                    'required'    => true,
                    'arg_options' => [
                        'sanitize_callback' => 'absint',
                    ],
                ],
                'created_by' => [
                    'description' => __( 'Created by user', 'botlite' ),
                    'type'        => 'integer',
                    'context'     => [ 'view', 'edit' ],
                    'arg_options' => [
                        'sanitize_callback' => 'absint',
                    ],
                ],
                'updated_by' => [
                    'description' => __( 'Updated by user', 'botlite' ),
                    'type'        => 'integer',
                    'context'     => [ 'view', 'edit' ],
                    'arg_options' => [
                        'sanitize_callback' => 'absint',
                    ],
                ],
                'created_at' => [
                    'description' => __( 'Created at time', 'botlite' ),
                    'type'        => 'string',
                    'context'     => [ 'view', 'edit' ],
                    'format'      => 'date-time',
                    'readonly'    => true,
                ],
                'updated_at' => [
                    'description' => __( 'Updated at time', 'botlite' ),
                    'type'        => 'string',
                    'context'     => [ 'view', 'edit' ],
                    'format'      => 'date-time',
                    'readonly'    => true,
                ],
                'deleted_at' => [
                    'description' => __( 'Deleted at time', 'botlite' ),
                    'type'        => 'string',
                    'context'     => [ 'view', 'edit' ],
                    'format'      => 'date-time',
                    'readonly'    => true,
                ],
            ],
        ];

        $this->schema = $schema;

        return $this->add_additional_fields_schema( $this->schema );
    }

    /**
     * Prepares a single email template for create or update.
     *
     * @since 0.3.0
     *
     * @param WP_REST_Request $request Request object.
     *
     * @return object|WP_Error
     */
    protected function prepare_item_for_database( $request ) {
        $data = [];
        $data['title']       = $request['title'];
        $data['slug']        = $this->generate_unique_slug( $request );
        $data['description'] = $request['description'];
        $data['city_id']  = $request['city_id'];
        $data['is_active']   = $request['is_active'];
        $data['alert_type_id'] = $request['alert_type_id'];

        if ( empty( $request['id'] ) ) {
            $data['created_by'] = empty( $request['created_by'] ) ? get_current_user_id() : absint( $request['created_by'] );
            $data['created_at']  = empty( $request['created_at'] ) ? current_datetime()->format( 'Y-m-d H:i:s' ) : $request['created_at'];
        } else {
            $data['updated_by'] = empty( $request['updated_by'] ) ? get_current_user_id() : absint( $request['updated_by'] );
            $data['updated_at']  = empty( $request['updated_at'] ) ? current_datetime()->format( 'Y-m-d H:i:s' ) : $request['updated_at'];
        }

        return $data;
    }

    /**
     * Prepares the item for the REST response.
     *
     * @since 0.3.0
     *
     * @param Alert            $item    WordPress representation of the item
     * @param WP_REST_Request $request request object
     *
     * @return WP_Error|WP_REST_Response
     */
    public function prepare_item_for_response( $item, $request ) {
        $data = [];

        $data = Alert::to_array( $item );

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
     * Sanitize alert slug for uniqueness.
     *
     * @since 0.3.0
     *
     * @param string $slug
     * @param WP_REST_Request $request
     *
     * @return WP_Error|string
     */
    public function sanitize_alert_slug( $slug, $request ) {
        global $wpdb;

        $slug          = sanitize_title( $slug );
        $id            = isset( $request['id'] ) ? $request['id'] : 0;
        $args['count'] = 1;

        if ( ! empty( $id ) ) {
            $args['where'][] = $wpdb->prepare( 'id != %d AND slug = %s', $id, $slug );
        } else {
            $args['where'][] = $wpdb->prepare( 'slug = %s', $slug );
        }

        $total_found = wp_bot_lite()->alerts->all( $args );

        if ( $total_found > 0 ) {
            return new WP_Error(
                'bot_lite_rest_slug_exists', __( 'Alert slug already exists.', 'botlite' ), [
					'status' => 400,
				]
            );
        }

        return sanitize_title( $slug );
    }

    /**
     * Generate unique slug if no slug is provided.
     *
     * @since 0.3.0
     *
     * @param WP_REST_Request $request
     *
     * @return string
     */
    public function generate_unique_slug( WP_REST_Request $request ) {
        $slug = $request['slug'];

        if ( empty( $slug ) ) {
            $slug = sanitize_title( $request['title'] );
            $slug = str_replace( ' ', '-', $slug );

            // Auto-generate only for create page.
            if ( empty( $request['id'] ) ) {
                $existing_alert = wp_bot_lite()->alerts->get(
                    [
						'key' => 'slug',
						'value' => $slug,
					]
                );

                // If error, means, there is no slug by this slug
                if ( empty( $existing_alert ) ) {
                    return $slug;
                }

                return $this->generate_beautiful_slug( $slug );
            }
        }

        return $slug;
    }

    /**
     * Generate beautiful slug.
     *
     * @since 0.3.1
     *
     * @param string $slug
     * @param integer $i
     *
     * @return string
     */
    public function generate_beautiful_slug( string $slug = '', $i = 1 ): string {
        while ( true ) {
            $new_slug     = $slug . '-' . $i;
            $existing_alert = wp_bot_lite()->alerts->get(
                [
                    'key' => 'slug',
                    'value' => $new_slug,
                ]
            );

            if ( empty( $existing_alert ) ) {
                return $new_slug;
            } else {
                $this->generate_beautiful_slug( $slug, $i + 1 );
            }

            $i++;
        }
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

        $params['limit']['default']   = 10;
        $params['search']['default']  = '';
        $params['orderby']['default'] = 'id';
        $params['order']['default']   = 'DESC';

        return $params;
    }
}
