<?php

namespace Dearvn\BotLite\Alerts;

use Dearvn\BotLite\Abstracts\BaseModel;

/**
 * AlertType class.
 *
 * @since 0.3.0
 */
class AlertType extends BaseModel {

    /**
     * Table Name.
     *
     * @var string
     */
    protected $table = 'botlite_alert_types';

    /**
     * Alert types item to a formatted array.
     *
     * @since 0.3.0
     *
     * @param object $alert_type
     *
     * @return array
     */
    public static function to_array( object $alert_type ): array {
        return [
            'id'          => (int) $alert_type->id,
            'name'        => $alert_type->name,
            'slug'        => $alert_type->slug,
            'description' => $alert_type->description,
            'created_at'  => $alert_type->created_at,
            'updated_at'  => $alert_type->updated_at,
        ];
    }
}
