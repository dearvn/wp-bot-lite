<?php

namespace Dearvn\LandLite\Products;

use Dearvn\LandLite\Abstracts\BaseModel;

/**
 * Product class.
 *
 * @since 0.3.0
 */
class Product extends BaseModel {

    /**
     * Table Name.
     *
     * @var string
     */
    protected $table = 'landlite_products';

    /**
     * Prepare datasets for database operation.
     *
     * @since 0.3.0
     *
     * @param array $request
     * @return array
     */
    public function prepare_for_database( array $data ): array {
        $defaults = [
            'title'       => '',
            'slug'        => '',
            'description' => '',
            'city_id'  => 0,
            'is_active'   => 1,
            'product_type_id' => null,
            'created_by'  => get_current_user_id(),
            'created_at'  => current_datetime()->format( 'Y-m-d H:i:s' ),
            'updated_at'  => current_datetime()->format( 'Y-m-d H:i:s' ),
        ];

        $data = wp_parse_args( $data, $defaults );

        // Sanitize template data
        return [
            'title'       => $this->sanitize( $data['title'], 'text' ),
            'slug'        => $this->sanitize( $data['slug'], 'text' ),
            'description' => $this->sanitize( $data['description'], 'block' ),
            'city_id'  => $this->sanitize( $data['city_id'], 'number' ),
            'is_active'   => $this->sanitize( $data['is_active'], 'switch' ),
            'product_type_id' => $this->sanitize( $data['product_type_id'], 'number' ),
            'created_by'  => $this->sanitize( $data['created_by'], 'number' ),
            'created_at'  => $this->sanitize( $data['created_at'], 'text' ),
            'updated_at'  => $this->sanitize( $data['updated_at'], 'text' ),
        ];
    }

    /**
     * Products item to a formatted array.
     *
     * @since 0.3.0
     *
     * @param object $product
     *
     * @return array
     */
    public static function to_array( ?object $product ): array {
        $product_type = static::get_product_type( $product );

        $data = [
            'id'          => (int) $product->id,
            'title'       => $product->title,
            'slug'        => $product->slug,
            'product_type'    => $product_type,
            'is_remote'   => static::get_is_remote( $product_type ),
            'status'      => ProductStatus::get_status_by_product( $product ),
            'city'     => static::get_product_city( $product ),
            'description' => $product->description,
            'created_at'  => $product->created_at,
            'updated_at'  => $product->updated_at,
        ];

        return $data;
    }

    /**
     * Get product type of a product.
     *
     * @since 0.3.0
     *
     * @param object $product
     *
     * @return object|null
     */
    public static function get_product_type( ?object $product ): ?object {
        $product_type = new ProductType();

        $columns = 'id, name, slug';
        return $product_type->get( (int) $product->product_type_id, $columns );
    }

    /**
     * Get if product is a remote product or not.
     *
     * We'll fetch this from product_type_id.
     * If product type is for remote, then it's a remote product.
     *
     * @param object $product_type
     * @return boolean
     */
    public static function get_is_remote( ?object $product_type ): bool {
        if ( empty( $product_type ) ) {
            return false;
        }

        return $product_type->slug === 'remote';
    }

    /**
     * Get city of a product.
     *
     * @since 0.3.0
     *
     * @param object $product
     *
     * @return null | array
     */
    public static function get_product_city( ?object $product ): ?array {
        if ( empty( $product->city_id ) ) {
            return null;
        }

        $user = get_user_by( 'id', $product->city_id );

        if ( empty( $user ) ) {
            return null;
        }

        return [
            'id'         => $product->city_id,
            'name'       => $user->display_name,
            'avatar_url' => get_avatar_url( $user->ID ),
        ];
    }
}
