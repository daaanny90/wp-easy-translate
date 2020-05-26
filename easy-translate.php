<?php
/**
 * Plugin Name:       Easy Translate
 * Plugin URI:        https://dannyspina.com/easy-translate
 * Description:       Easy translate allows you to write your content in your mother tongue and to publish it in over 99 languages just pressing the publish button.
 * Version:           0.0.1
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Danny Spina
 * Author URI:        https://dannyspina.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       easytranslate
 * Domain Path:       /languages
 */

/*
 * Plugin autoloader
 */
require_once plugin_dir_path( __FILE__ ) . 'inc/autoloader.php';

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/*
 * Add style for metabox
 */
add_action( 'admin_enqueue_scripts', 'easytranslate_metabox_style' );
if ( ! function_exists( 'easytranslate_metabox_style' ) ) {
	function easytranslate_metabox_style() {
		wp_enqueue_style( 'easytranslate-metabox-style', plugins_url( 'admin/easy-translate-style.css', __FILE__ ) );
	}
}

/*
 * Modify the content and the title when the post is saved into the database
 */
add_filter( 'wp_insert_post_data', 'easytranslate_autotranslate', 99, 2 );
if ( ! function_exists( 'easytranslate_autotranslate' ) ) {
	function easytranslate_autotranslate( $data, $postarr ) {
		$metabox_translate = ( array_key_exists( 'easytranslate-metabox-translate', $_POST ) ) ? true : false;
		$yandex_copy       = '<hr class="wp-block-separator">
						<p>This text was written in Italian. The english translation is powered by <a href="http://translate.yandex.com">Yandex.Translate</a>.</p>';
		$yandex_copy_clean = 'translation is powered by';

		// Do not perform translation in customizer
		if ( $data['post_type'] != 'customize_changeset' ) {
			$options = get_option( 'easytranslate_options' );
			// Perform the translation only if is an update or a publication and an API key is saved, not when a post is created (there is nothing to translate)
			if ( $data['post_status'] != 'auto-draft' && $options['easytranslate_field_api'] && $metabox_translate ) {
				$translate            = new EasyTranslate\Classes\Translate( $options );
				$data['post_title']   = $translate->translateTitle( $data['post_title'] );
				$data['post_content'] = $translate->translateText( $data['post_content'] );

				// Add the Yandex copy only once
				$copy = ( strpos( $data['post_content'], $yandex_copy_clean ) !== false ) ? true : false;
				if ( ! $copy ) {
					$data['post_content'] .= $yandex_copy;
				}

			}
		}

		return $data;
	}
}

/*
 * Settings page functionality
 */
include plugin_dir_path( __FILE__ ) . 'admin/easy-translate-admin.php';
include plugin_dir_path( __FILE__ ) . 'admin/easy-translate-metabox.php';
