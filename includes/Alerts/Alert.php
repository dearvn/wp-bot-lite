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
            'created_at'    => '',
            'contracts'     => 0,
            'position_size' => 0
        ];

        $data = wp_parse_args( $data, $defaults );

        // Sanitize template data
        return [
            'name'       => $this->sanitize( $data['name'], 'text' ),
            'close'        => $this->sanitize( $data['close'], 'number' ),
            'ticker' => $this->sanitize( $data['ticker'], 'text' ),
            'exchange' => $this->sanitize( $data['exchange'], 'text' ),
            'type' => $this->sanitize( $data['type'], 'text' ),
            'interval'  => $this->sanitize( $data['interval'], 'number' ),
            'created_at'  => $this->sanitize( $data['created_at'], 'text' ),
            'contracts'     => $this->sanitize( $data['contracts'], 'number' ),
            'position_size' => $this->sanitize( $data['position_size'], 'number' )
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
        $data = [
            'id'            => (int) $alert->id,
            'name'          => $alert->name,
            'ticker'        => $alert->ticker,
            'type'          => $alert->type,
            'exchange'      => $alert->exchange,
            'interval'      => $alert->interval,
            'close'         => $alert->close,
            'created_at'    => date('m/d/Y H:i', strtotime($alert->created_at)),
            'contracts'     => $alert->contracts,
            'position_size' => $alert->position_size
        ];

        return $data;
    }
}
