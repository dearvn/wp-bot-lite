<?php

namespace Dearvn\BotLite\Orders;

/**
 * OrderStatus class.
 *
 * @since 0.3.0
 */
class OrderStatus {

    /**
     * Draft status.
     *
     * @since 0.3.0
     */
    const DRAFT = 'draft';

    /**
     * Published status.
     *
     * @since 0.3.0
     */
    const PUBLISHED = 'published';

    /**
     * Trashed status.
     *
     * @since 0.3.0
     */
    const TRASHED = 'trashed';

    /**
     * Get order status.
     *
     * @since 0.3.0
     *
     * @param object $order
     */
    public static function get_status_by_order( object $order ): string {
        if ( ! empty( $order->deleted_at ) ) {
            return self::TRASHED;
        }

        if ( $order->is_active ) {
            return self::PUBLISHED;
        }

        return self::DRAFT;
    }
}
