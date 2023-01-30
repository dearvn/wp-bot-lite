<?php

namespace Dearvn\BotLite\Databases\Seeder;

use Dearvn\BotLite\Abstracts\DBSeeder;
use Dearvn\BotLite\Common\Keys;
use Dearvn\BotLite\Settings\Setting;

/**
 * Settings Seeder class.
 *
 * Seed the initial settings.
 */
class SettingsSeeder extends DBSeeder {

    /**
     * Run Settings seeder.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function run() {
        // Check if there is already a seeder runs for this plugin.
        $already_seeded = (bool) get_option( Keys::SETTING_SEEDER_RAN, false );
        if ( $already_seeded ) {
            return;
        }

        // Generate settings.
        update_option(
            Setting::SETTING_META_KEY, [
				'enable_trading' => 'no',
				'api_key'   => '',
                'secret_key'   => '',
			], true
        );

        // Update that seeder already runs.
        update_option( Keys::SETTING_SEEDER_RAN, true );
    }
}
