<?php

namespace Dearvn\BotLite\Settings;

class Setting {

	/**
	 * @var string
	 */
	public const SETTING_META_KEY = 'bot_lite_settings';

	/**
	 * @var string Binance API key.
	 */
	private $api_key;

	/**
	 * @var string Binance Secret key.
	 */
	private $secret_key;

	/**
	 * @return string
	 */
	public function get_api_key(): string {
		return $this->api_key;
	}

	/**
	 * @param string $api_key
	 *
	 * @return self
	 */
	public function set_api_key( string $api_key ): Setting {
		$this->api_key = $api_key;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_secret_key(): string {
		return $this->secret_key;
	}

	/**
	 * @param string $secret_key
	 *
	 * @return self
	 */
	public function set_secret_key( string $secret_key ): Setting {
		$this->secret_key = $secret_key;

		return $this;
	}
}
