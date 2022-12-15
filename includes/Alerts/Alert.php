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
            'name'          => '',
            'close'         => '',
            'type'          => '',
            'interval'      => '',
            'ticker'        => '',
            'exchange'      => '',
            'created_at'  => current_datetime()->format( 'Y-m-d H:i:s' ),
        ];

        $data = wp_parse_args( $data, $defaults );

        // Sanitize template data
        return [
            'name'       => $this->sanitize( $data['name'], 'text' ),
            'close'        => $this->sanitize( $data['close'], 'number' ),
            'ticker' => $this->sanitize( $data['ticker'], 'ticker' ),
            'exchange' => $this->sanitize( $data['exchange'], 'exchange' ),
            'type' => $this->sanitize( $data['type'], 'type' ),
            'interval'  => $this->sanitize( $data['interval'], 'number' ),
            'created_at'  => $this->sanitize( $data['created_at'], 'text' )
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
            'id'            => (int) $alert->id,
            'name'          => $alert->name,
            'ticker'        => $alert->ticker,
            'type'          => $alert->type,
            'exchange'      => $alert->exchange,
            'interval'      => $alert->interval,
            'close'                 => $alert->close,
            'created_at'    => date('m/d/Y H:i', strtotime($alert->created_at)),
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
