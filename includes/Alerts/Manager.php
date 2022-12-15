<?php

namespace Dearvn\BotLite\Alerts;

class Manager {

    /**
     * Alert class.
     *
     * @var Alert
     */
    public $alert;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->alert = new Alert();
    }

    /**
     * Get all alerts by criteria.
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

        $alerts = $this->alert->all( $args );

        if ( $args['count'] ) {
            return (int) $alerts;
        }

        return $alerts;
    }

    /**
     * Get single alert by id|slug.
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

        return $this->alert->get_by( $args['key'], $args['value'] );
    }

    /**
     * Create a new alert.
     *
     * @since 0.3.0
     *
     * @param array $data
     *
     * @return int | WP_Error $id
     */
    public function create( $data ) {
        // Prepare alert data for database-insertion.
        $alert_data = $this->alert->prepare_for_database( $data );

        // Create alert now.
        $alert_id = $this->alert->create(
            $alert_data,
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

        if ( ! $alert_id ) {
            return new \WP_Error( 'botlite_alert_create_failed', __( 'Failed to create alert.', 'botlite' ) );
        }

        /**
         * Fires after a alert has been created.
         *
         * @since 0.3.0
         *
         * @param int   $alert_id
         * @param array $alert_data
         */
        do_action( 'botlite_alerts_created', $alert_id, $alert_data );

        return $alert_id;
    }

    /**
     * Update alert.
     *
     * @since 0.3.0
     *
     * @param array $data
     * @param int   $alert_id
     *
     * @return int | WP_Error $id
     */
    public function update( array $data, int $alert_id ) {
        // Prepare alert data for database-insertion.
        $alert_data = $this->alert->prepare_for_database( $data );

        // Update alert.
        $updated = $this->alert->update(
            $alert_data,
            [
                'id' => $alert_id,
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
            return new \WP_Error( 'botlite_alert_update_failed', __( 'Failed to update alert.', 'botlite' ) );
        }

        if ( $updated >= 0 ) {
            /**
             * Fires after a alert is being updated.
             *
             * @since 0.3.0
             *
             * @param int   $alert_id
             * @param array $alert_data
             */
            do_action( 'botlite_alerts_updated', $alert_id, $alert_data );

            return $alert_id;
        }

        return new \WP_Error( 'botlite_alert_update_failed', __( 'Failed to update the alert.', 'botlite' ) );
    }

    /**
     * Delete alerts data.
     *
     * @since 0.3.0
     *
     * @param array|int $alert_ids
     *
     * @return int|WP_Error
     */
    public function delete( $alert_ids ) {
        if ( is_array( $alert_ids ) ) {
            $alert_ids = array_map( 'absint', $alert_ids );
        } else {
            $alert_ids = [ absint( $alert_ids ) ];
        }

        try {
            $this->alert->query( 'START TRANSACTION' );

            $total_deleted = 0;
            foreach ( $alert_ids as $alert_id ) {
                $deleted = $this->alert->delete(
                    [
                        'id' => $alert_id,
                    ],
                    [
                        '%d',
                    ]
                );

                if ( $deleted ) {
                    $total_deleted += intval( $deleted );
                }

                /**
                 * Fires after a alert has been deleted.
                 *
                 * @since 0.3.0
                 *
                 * @param int $alert_id
                 */
                do_action( 'botlite_alert_deleted', $alert_id );
            }

            $this->alert->query( 'COMMIT' );

            return $total_deleted;
        } catch ( \Exception $e ) {
            $this->alert->query( 'ROLLBACK' );

            return new \WP_Error( 'botlite-alert-delete-error', $e->getMessage() );
        }
    }
}
