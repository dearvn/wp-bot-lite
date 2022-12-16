<?php

namespace Dearvn\BotLite\Orders;

use Dearvn\BotLite\Abstracts\BaseModel;

/**
 * Order class.
 *
 * @since 0.3.0
 */
class Order extends BaseModel {

    /**
     * Table Name.
     *
     * @var string
     */
    protected $table = 'botlite_orders';

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
            'order_id' => '',
            'signal' => '',
            'logic' => '',
            'contracts' => '',
            'type'          => '',
            'interval'      => '',
            'ticker'        => '',
            'exchange'      => '',
            'entry_price' => '',
            'entry_at' => current_datetime()->format( 'Y-m-d H:i:s' ),
            'exit_price'         => '',
            'exit_at'  => '',
        ];

        $data = wp_parse_args( $data, $defaults );

        // Sanitize template data
        return [
            'name'       => $this->sanitize( $data['name'], 'text' ),
            'ticker' => $this->sanitize( $data['ticker'], 'text' ),
            'exchange' => $this->sanitize( $data['exchange'], 'text' ),
            'type' => $this->sanitize( $data['type'], 'text' ),
            'interval'  => $this->sanitize( $data['interval'], 'number' ),
            'order_id' => $this->sanitize( $data['order_id'], 'text' ),
            'signal' => $this->sanitize( $data['signal'], 'text' ),
            'logic' => $this->sanitize( $data['logic'], 'text' ),
            'contracts'  => $this->sanitize( $data['contracts'], 'number' ),
            'entry_price'        => $this->sanitize( $data['entry_price'], 'number' ),
            'entry_at'  => $this->sanitize( $data['entry_at'], 'text' ),
            'exit_price'        => $this->sanitize( $data['exit_price'], 'number' ),
            'exit_at'  => $this->sanitize( $data['exit_at'], 'text' )
        ];
    }

    /**
     * Orders item to a formatted array.
     *
     * @since 0.3.0
     *
     * @param object $order
     *
     * @return array
     */
    public static function to_array( ?object $order ): array {
        $gain_loss = 0;
        if ($order->type == 'long') {
            $gain_loss = $order->entry_price - $order->exit_price;
        } else if ($order->type == 'short') {
            $gain_loss = $order->exit_price - $order->entry_price;
        }
        $data = [
            'id'            => (int) $order->id,
            'name'          => $order->name,
            'ticker'        => $order->ticker,
            'type'          => $order->type,
            'exchange'      => $order->exchange,
            'interval'      => $order->interval,
            'order_id' => $order->order_id,
            'signal' => $order->signal,
            'logic' => $order->logic,
            'contracts' => $order->contracts,
            'entry_price' => $order->entry_price,
            'entry_at' => $order->entry_at ? date('m/d/Y H:i', strtotime($order->entry_at)) : '',
            'exit_price'         => $order->exit_price,
            'exit_at'  => $order->exit_at ? date('m/d/Y H:i', strtotime($order->exit_at)) : '',
            'gain_loss' => $gain_loss
        ];

        return $data;
    }

    /**
     * Get order type of a order.
     *
     * @since 0.3.0
     *
     * @param object $order
     *
     * @return object|null
     */
    public static function get_order_type( ?object $order ): ?object {
        $order_type = new OrderType();

        $columns = 'id, name, slug';
        return $order_type->get( (int) $order->order_type_id, $columns );
    }

    /**
     * Get if order is a remote order or not.
     *
     * We'll fetch this from order_type_id.
     * If order type is for remote, then it's a remote order.
     *
     * @param object $order_type
     * @return boolean
     */
    public static function get_is_remote( ?object $order_type ): bool {
        if ( empty( $order_type ) ) {
            return false;
        }

        return $order_type->slug === 'remote';
    }

    /**
     * Get city of a order.
     *
     * @since 0.3.0
     *
     * @param object $order
     *
     * @return null | array
     */
    public static function get_order_city( ?object $order ): ?array {
        if ( empty( $order->city_id ) ) {
            return null;
        }

        $user = get_user_by( 'id', $order->city_id );

        if ( empty( $user ) ) {
            return null;
        }

        return [
            'id'         => $order->city_id,
            'name'       => $user->display_name,
            'avatar_url' => get_avatar_url( $user->ID ),
        ];
    }
}
