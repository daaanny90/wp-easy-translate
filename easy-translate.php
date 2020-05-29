<?php
/**
 * Plugin Name:       Easy Translate
 * Plugin URI:        https://dannyspina.com/blog/easy-translate
 * Description:       Easy translate allows you to write your content in your native language and to publish it in over 90 languages just pressing the publish button.
 * Version:           1.0.0-beta
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
 * Add sidebar functionality
 */
add_action( 'init', 'easytranslate_sidebar_plugin_register' );
if ( ! function_exists( 'easytranslate_sidebar_plugin_register' ) ) {
	function easytranslate_sidebar_plugin_register() {
		wp_register_script(
			'plugin-sidebar-js',
			plugins_url( 'sidebar/js/plugin-sidebar.js', __FILE__ ),
			array( 'wp-plugins', 'wp-edit-post', 'wp-element', 'wp-components' )
		);
		wp_register_style(
			'plugin-sidebar-css',
			plugins_url( 'sidebar/css/plugin-sidebar.css', __FILE__ )
		);
	}
}

add_action( 'enqueue_block_editor_assets', 'easytranslate_sidebar_plugin_script_enqueue' );
if ( ! function_exists( 'easytranslate_sidebar_plugin_script_enqueue' ) ) {
	function easytranslate_sidebar_plugin_script_enqueue() {
		$options = get_option( 'easytranslate_options' );
		wp_enqueue_script( 'plugin-sidebar-js' );
		wp_localize_script( 'plugin-sidebar-js', 'options', $options );
	}
}

add_action( 'enqueue_block_assets', 'easytranslate_sidebar_plugin_style_enqueue' );
if ( ! function_exists( 'easytranslate_sidebar_plugin_style_enqueue' ) ) {
	function easytranslate_sidebar_plugin_style_enqueue() {
		wp_enqueue_style( 'plugin-sidebar-css' );
	}
}

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
add_filter( 'wp_insert_post_data', 'easytranslate_autotranslate', 10, 2 );
if ( ! function_exists( 'easytranslate_autotranslate' ) ) {
	function easytranslate_autotranslate( $data, $postarr ) {
		$metabox_translate = ( array_key_exists( 'easytranslate-metabox-translate', $_POST ) ) ? true : false;
		$yandex_copy       = '<hr class="wp-block-separator">
						<p>Powered by <a href="http://translate.yandex.com">Yandex.Translate</a>.</p>';
		$yandex_copy_clean = 'Powered by';

		// Do not perform translation in customizer
		if ( $data['post_type'] != 'customize_changeset' ) {
			$options = get_option( 'easytranslate_options' );
			// Perform the translation only if is an update or a publication and an API key is saved, not when a post is created (there is nothing to translate)
			if ( $data['post_status'] != 'auto-draft' && $options['easytranslate_field_api'] && $metabox_translate ) {
				$translate            = new EasyTranslate\Classes\Translate( $options );
				$data['post_title']   = $translate->translateTitle( $data['post_title'] );
				$data['post_content'] = $translate->translateText( $data['post_content'] );
				$data['post_name']    = $translate->translatePermalink( $data['post_name'] );

				// Add the Yandex copy only once
				$copy = ( strpos( $data['post_content'], $yandex_copy_clean ) !== false ) ? true : false;
				if ( ! $copy && ! $options['easytranslate_signature'] ) {
					$data['post_content'] .= $yandex_copy;
				}
			}
		}

		return $data;
	}
}

add_action( 'post_submitbox_misc_actions', 'my_post_submitbox_misc_actions' );

function my_post_submitbox_misc_actions( $post ) {
	?>
    <div class="misc-pub-section my-options">
        <label for="my_custom_post_action">My Option</label><br/>
        <select id="my_custom_post_action" name="my_custom_post_action">
            <option value="1">First Option goes here</option>
            <option value="2">Second Option goes here</option>
        </select>
    </div>
	<?php
}

/*
 * Settings page functionality
 */
include plugin_dir_path( __FILE__ ) . 'admin/easy-translate-admin.php';
include plugin_dir_path( __FILE__ ) . 'admin/easy-translate-metabox.php';
