<?php

namespace Dearvn\LandLite\Products;

class Manager {

    /**
     * Product class.
     *
     * @var Product
     */
    public $product;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->product = new Product();
    }

    /**
     * Get all products by criteria.
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
            $args['where'][] = $wpdb->prepare( ' title LIKE %s OR description LIKE %s ', $like, $like );
        }

        if ( ! empty( $args['where'] ) ) {
            $args['where'] = ' WHERE ' . implode( ' AND ', $args['where'] );
        } else {
            $args['where'] = '';
        }

        $products = $this->product->all( $args );

        if ( $args['count'] ) {
            return (int) $products;
        }

        return $products;
    }

    /**
     * Get single product by id|slug.
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

        return $this->product->get_by( $args['key'], $args['value'] );
    }

    /**
     * Create a new product.
     *
     * @since 0.3.0
     *
     * @param array $data
     *
     * @return int | WP_Error $id
     */
    public function create( $data ) {
        // Prepare product data for database-insertion.
        $product_data = $this->product->prepare_for_database( $data );

        // Create product now.
        $product_id = $this->product->create(
            $product_data,
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

        if ( ! $product_id ) {
            return new \WP_Error( 'landlite_product_create_failed', __( 'Failed to create product.', 'landlite' ) );
        }

        /**
         * Fires after a product has been created.
         *
         * @since 0.3.0
         *
         * @param int   $product_id
         * @param array $product_data
         */
        do_action( 'landlite_products_created', $product_id, $product_data );

        return $product_id;
    }

    /**
     * Update product.
     *
     * @since 0.3.0
     *
     * @param array $data
     * @param int   $product_id
     *
     * @return int | WP_Error $id
     */
    public function update( array $data, int $product_id ) {
        // Prepare product data for database-insertion.
        $product_data = $this->product->prepare_for_database( $data );

        // Update product.
        $updated = $this->product->update(
            $product_data,
            [
                'id' => $product_id,
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
            return new \WP_Error( 'landlite_product_update_failed', __( 'Failed to update product.', 'landlite' ) );
        }

        if ( $updated >= 0 ) {
            /**
             * Fires after a product is being updated.
             *
             * @since 0.3.0
             *
             * @param int   $product_id
             * @param array $product_data
             */
            do_action( 'landlite_products_updated', $product_id, $product_data );

            return $product_id;
        }

        return new \WP_Error( 'landlite_product_update_failed', __( 'Failed to update the product.', 'landlite' ) );
    }

    /**
     * Delete products data.
     *
     * @since 0.3.0
     *
     * @param array|int $product_ids
     *
     * @return int|WP_Error
     */
    public function delete( $product_ids ) {
        if ( is_array( $product_ids ) ) {
            $product_ids = array_map( 'absint', $product_ids );
        } else {
            $product_ids = [ absint( $product_ids ) ];
        }

        try {
            $this->product->query( 'START TRANSACTION' );

            $total_deleted = 0;
            foreach ( $product_ids as $product_id ) {
                $deleted = $this->product->delete(
                    [
                        'id' => $product_id,
                    ],
                    [
                        '%d',
                    ]
                );

                if ( $deleted ) {
                    $total_deleted += intval( $deleted );
                }

                /**
                 * Fires after a product has been deleted.
                 *
                 * @since 0.3.0
                 *
                 * @param int $product_id
                 */
                do_action( 'landlite_product_deleted', $product_id );
            }

            $this->product->query( 'COMMIT' );

            return $total_deleted;
        } catch ( \Exception $e ) {
            $this->product->query( 'ROLLBACK' );

            return new \WP_Error( 'landlite-product-delete-error', $e->getMessage() );
        }
    }
}
