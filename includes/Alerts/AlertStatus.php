<?php

namespace Dearvn\BotLite\Alerts;

/**
 * AlertStatus class.
 *
 * @since 0.3.0
 */
class AlertStatus {

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
     * Get alert status.
     *
     * @since 0.3.0
     *
     * @param object $alert
     */
    public static function get_status_by_alert( object $alert ): string {
        if ( ! empty( $alert->deleted_at ) ) {
            return self::TRASHED;
        }

        if ( $alert->is_active ) {
            return self::PUBLISHED;
        }

        return self::DRAFT;
    }
}
