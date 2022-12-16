<?php

namespace Dearvn\BotLite\Orders;

class Manager {

    /**
     * Order class.
     *
     * @var Order
     */
    public $order;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->order = new Order();
    }

    /**
     * Get all orders by criteria.
     *
     * @since 0.3.0
     * @since 0.3.1 Fixed counting return type as integer.
     *
     * @param array $args
     * @return array|object|string|int
     */
    public function all( array $args = [] ) {
        $defaults = [
            'page'     => 1,
            'per_page' => 10,
            'orderby'  => 'id',
            'order'    => 'DESC',
            'search'   => '',
            'count'    => false,
            'where'    => [],
        ];

        $args = wp_parse_args( $args, $defaults );

        if ( ! empty( $args['search'] ) ) {
            global $wpdb;
            $like = '%' . $wpdb->esc_like( sanitize_text_field( wp_unslash( $args['search'] ) ) ) . '%';
            $args['where'][] = $wpdb->prepare( ' name LIKE %s ', $like, $like );
        }

        if ( ! empty( $args['where'] ) ) {
            $args['where'] = ' WHERE ' . implode( ' AND ', $args['where'] );
        } else {
            $args['where'] = '';
        }

        $orders = $this->order->all( $args );

        if ( $args['count'] ) {
            return (int) $orders;
        }

        return $orders;
    }

    /**
     * Get single order by id|slug.
     *
     * @since 0.3.0
     *
     * @param array $args
     * @return array|object|null
     */
    public function get( array $args = [] ) {
        $defaults = [
            'key' => 'id',
            'value' => '',
        ];

        $args = wp_parse_args( $args, $defaults );

        if ( empty( $args['value'] ) ) {
            return null;
        }

        return $this->order->get_by( $args['key'], $args['value'] );
    }

    /**
     * Create a new order.
     *
     * @since 0.3.0
     *
     * @param array $data
     *
     * @return int | WP_Error $id
     */
    public function create( $data ) {
        // Prepare order data for database-insertion.
        $order_data = $this->order->prepare_for_database( $data );

        // Create order now.
        $order_id = $this->order->create(
            $order_data,
            [
                '%s',
                '%s',
                '%s',
                '%d',
                '%d',
                '%d',
                '%d',
                '%s',
                '%s',
            ]
        );

        if ( ! $order_id ) {
            return new \WP_Error( 'botlite_order_create_failed', __( 'Failed to create order.', 'botlite' ) );
        }

        /**
         * Fires after a order has been created.
         *
         * @since 0.3.0
         *
         * @param int   $order_id
         * @param array $order_data
         */
        do_action( 'botlite_orders_created', $order_id, $order_data );

        return $order_id;
    }

    /**
     * Update order.
     *
     * @since 0.3.0
     *
     * @param array $data
     * @param int   $order_id
     *
     * @return int | WP_Error $id
     */
    public function update( array $data, int $order_id ) {
        // Prepare order data for database-insertion.
        $order_data = $this->order->prepare_for_database( $data );

        // Update order.
        $updated = $this->order->update(
            $order_data,
            [
                'id' => $order_id,
            ],
            [
                '%s',
                '%s',
                '%s',
                '%d',
                '%d',
                '%d',
                '%d',
                '%s',
                '%s',
            ],
            [
                '%d',
            ]
        );

        if ( ! $updated ) {
            return new \WP_Error( 'botlite_order_update_failed', __( 'Failed to update order.', 'botlite' ) );
        }

        if ( $updated >= 0 ) {
            /**
             * Fires after a order is being updated.
             *
             * @since 0.3.0
             *
             * @param int   $order_id
             * @param array $order_data
             */
            do_action( 'botlite_orders_updated', $order_id, $order_data );

            return $order_id;
        }

        return new \WP_Error( 'botlite_order_update_failed', __( 'Failed to update the order.', 'botlite' ) );
    }

    /**
     * Delete orders data.
     *
     * @since 0.3.0
     *
     * @param array|int $order_ids
     *
     * @return int|WP_Error
     */
    public function delete( $order_ids ) {
        if ( is_array( $order_ids ) ) {
            $order_ids = array_map( 'absint', $order_ids );
        } else {
            $order_ids = [ absint( $order_ids ) ];
        }

        try {
            $this->order->query( 'START TRANSACTION' );

            $total_deleted = 0;
            foreach ( $order_ids as $order_id ) {
                $deleted = $this->order->delete(
                    [
                        'id' => $order_id,
                    ],
                    [
                        '%d',
                    ]
                );

                if ( $deleted ) {
                    $total_deleted += intval( $deleted );
                }

                /**
                 * Fires after a order has been deleted.
                 *
                 * @since 0.3.0
                 *
                 * @param int $order_id
                 */
                do_action( 'botlite_order_deleted', $order_id );
            }

            $this->order->query( 'COMMIT' );

            return $total_deleted;
        } catch ( \Exception $e ) {
            $this->order->query( 'ROLLBACK' );

            return new \WP_Error( 'botlite-order-delete-error', $e->getMessage() );
        }
    }
}
