<?php

namespace EasyTranslate\Classes;

use EasyTranslate;

class Translate {
	/**
	 * @var string
	 */
	protected $yandex_api_key;
	/**
	 * @var string
	 */
	protected $yandex_url = 'https://translate.yandex.net/api/v1.5/tr.json/translate?';
	/**
	 * @var string
	 */
	protected $lang = '&lang=';
	/**
	 * @var string
	 */
	protected $langFrom;
	/**
	 * @var string
	 */
	protected $langTo;

	/**
	 * @var string
	 */
	protected $format = '&format=html';

	/**
	 * Translate constructor.
	 *
	 * @param $secrets array The options set into the settings panel
	 */
	public function __construct( array $secrets ) {
		$api_key = $secrets['easytranslate_field_api'];

		$this->yandex_api_key = 'key=' . (string) $api_key . '&';
		$this->langFrom       = (string) $secrets['easytranslate_lang_1'];
		$this->langTo         = (string) $secrets['easytranslate_lang_2'];
	}

	/**
	 * @param $post_content string The post content
	 *
	 * @return string
	 */
	public function translateText( $post_content ) {
		return $this->translate( $post_content );
	}

	/**
	 * @param $post_title string The post title
	 *
	 * @return string
	 */
	public function translateTitle( $post_title ) {
		$clean_title = strip_tags( $post_title );

		return $this->translate( $clean_title );
	}

	/**
	 * @param $text string The text to send for translation
	 *
	 * @return string
	 */
	private function translate( $text ) {
		$clean_text           = '&text=' . urlencode( $text );
		$translation_request  = $this->yandex_url . $this->yandex_api_key . $clean_text . $this->lang . $this->langFrom . '-' . $this->langTo . $this->format;
		$translation_response = file_get_contents( $translation_request );
		$json_response        = json_decode( $translation_response, true );
		$translated_text      = $json_response['text'][0];

		return $translated_text;
	}

}