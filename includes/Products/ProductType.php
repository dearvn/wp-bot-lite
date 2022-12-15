<?php

namespace Dearvn\LandLite\Products;

use Dearvn\LandLite\Abstracts\BaseModel;

/**
 * ProductType class.
 *
 * @since 0.3.0
 */
class ProductType extends BaseModel {

    /**
     * Table Name.
     *
     * @var string
     */
    protected $table = 'landlite_product_types';

    /**
     * Product types item to a formatted array.
     *
     * @since 0.3.0
     *
     * @param object $product_type
     *
     * @return array
     */
    public static function to_array( object $product_type ): array {
        return [
            'id'          => (int) $product_type->id,
            'name'        => $product_type->name,
            'slug'        => $product_type->slug,
            'description' => $product_type->description,
            'created_at'  => $product_type->created_at,
            'updated_at'  => $product_type->updated_at,
        ];
    }
}
