<?php

namespace Dearvn\BotLite\Orders;

use Dearvn\BotLite\Abstracts\BaseModel;

/**
 * OrderType class.
 *
 * @since 0.3.0
 */
class OrderType extends BaseModel {

    /**
     * Table Name.
     *
     * @var string
     */
    protected $table = 'botlite_order_types';

    /**
     * Order types item to a formatted array.
     *
     * @since 0.3.0
     *
     * @param object $order_type
     *
     * @return array
     */
    public static function to_array( object $order_type ): array {
        return [
            'id'          => (int) $order_type->id,
            'name'        => $order_type->name,
            'slug'        => $order_type->slug,
            'description' => $order_type->description,
            'created_at'  => $order_type->created_at,
            'updated_at'  => $order_type->updated_at,
        ];
    }
}
