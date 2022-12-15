<?php

namespace Dearvn\LandLite\Products;

/**
 * ProductStatus class.
 *
 * @since 0.3.0
 */
class ProductStatus {

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
     * Get product status.
     *
     * @since 0.3.0
     *
     * @param object $product
     */
    public static function get_status_by_product( object $product ): string {
        if ( ! empty( $product->deleted_at ) ) {
            return self::TRASHED;
        }

        if ( $product->is_active ) {
            return self::PUBLISHED;
        }

        return self::DRAFT;
    }
}
