<?php


namespace EasyTranslate\Classes;

use EasyTranslate;

class Language {
	/**
	 * @var string
	 */
	protected $yandex_url = 'https://translate.yandex.net/api/v1.5/tr.json/getLangs?';
	/**
	 * @var string
	 */
	protected $yandex_api_key;

	/**
	 * Language constructor.
	 *
	 * @param $secrets
	 */
	public function __construct( array $secrets ) {
		$api_key = $secrets['easytranslate_field_api'];

		$this->yandex_api_key = 'key=' . $api_key . '&';
	}

	/**
	 * @return mixed
	 */
	public function getAvailableLanguages() {
		$request = $this->yandex_url . $this->yandex_api_key . '&ui=en';
		$languages_response = file_get_contents( $request );
		$json_response        = json_decode( $languages_response, true );
		return $json_response['langs'];
	}
}