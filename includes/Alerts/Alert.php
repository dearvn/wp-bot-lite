<?php

namespace Dearvn\BotLite\Alerts;

use Dearvn\BotLite\Abstracts\BaseModel;

/**
 * Alert class.
 *
 * @since 0.3.0
 */
class Alert extends BaseModel {

    /**
     * Table Name.
     *
     * @var string
     */
    protected $table = 'botlite_alerts';

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
            'alert_type_id' => null,
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
            'alert_type_id' => $this->sanitize( $data['alert_type_id'], 'number' ),
            'created_by'  => $this->sanitize( $data['created_by'], 'number' ),
            'created_at'  => $this->sanitize( $data['created_at'], 'text' ),
            'updated_at'  => $this->sanitize( $data['updated_at'], 'text' ),
        ];
    }

    /**
     * Alerts item to a formatted array.
     *
     * @since 0.3.0
     *
     * @param object $alert
     *
     * @return array
     */
    public static function to_array( ?object $alert ): array {
        $alert_type = static::get_alert_type( $alert );

        $data = [
            'id'          => (int) $alert->id,
            'title'       => $alert->title,
            'slug'        => $alert->slug,
            'alert_type'    => $alert_type,
            'is_remote'   => static::get_is_remote( $alert_type ),
            'status'      => AlertStatus::get_status_by_alert( $alert ),
            'city'     => static::get_alert_city( $alert ),
            'description' => $alert->description,
            'created_at'  => $alert->created_at,
            'updated_at'  => $alert->updated_at,
        ];

        return $data;
    }

    /**
     * Get alert type of a alert.
     *
     * @since 0.3.0
     *
     * @param object $alert
     *
     * @return object|null
     */
    public static function get_alert_type( ?object $alert ): ?object {
        $alert_type = new AlertType();

        $columns = 'id, name, slug';
        return $alert_type->get( (int) $alert->alert_type_id, $columns );
    }

    /**
     * Get if alert is a remote alert or not.
     *
     * We'll fetch this from alert_type_id.
     * If alert type is for remote, then it's a remote alert.
     *
     * @param object $alert_type
     * @return boolean
     */
    public static function get_is_remote( ?object $alert_type ): bool {
        if ( empty( $alert_type ) ) {
            return false;
        }

        return $alert_type->slug === 'remote';
    }

    /**
     * Get city of a alert.
     *
     * @since 0.3.0
     *
     * @param object $alert
     *
     * @return null | array
     */
    public static function get_alert_city( ?object $alert ): ?array {
        if ( empty( $alert->city_id ) ) {
            return null;
        }

        $user = get_user_by( 'id', $alert->city_id );

        if ( empty( $user ) ) {
            return null;
        }

        return [
            'id'         => $alert->city_id,
            'name'       => $user->display_name,
            'avatar_url' => get_avatar_url( $user->ID ),
        ];
    }
}
