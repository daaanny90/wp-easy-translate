<?php
/**
 * custom option and settings
 */
function easytranslate_settings_init() {
	// register a new setting for "easytranslate" page
	register_setting( 'easytranslate', 'easytranslate_options' );

	// register a new section in the "easytranslate" page
	add_settings_section(
		'easytranslate_section_api',
		__( 'Set your API Key', 'easytranslate' ),
		'easytranslate_section_api_input',
		'easytranslate'
	);

	// register a new field in the "easytranslate_section_developers" section, inside the "easytranslate" page
	add_settings_field(
		'easytranslate_field_api',
		__( 'API Key', 'easytranslate' ),
		'easytranslate_field_api_input',
		'easytranslate',
		'easytranslate_section_api',
		[
			'label_for'                 => 'easytranslate_field_api',
			'class'                     => 'easytranslate_row',
			'easytranslate_custom_data' => 'custom',
		]
	);

	add_settings_field(
		'easytranslate_always_translate',
		__( 'Keep translation always activated', 'easytranslate' ),
		'easytranslate_field_always_translate',
		'easytranslate',
		'easytranslate_section_api',
		[
			'label_for'                 => 'easytranslate_always_translate',
			'class'                     => 'easytranslate_row',
			'easytranslate_custom_data' => 'custom',
		]
	);

	// Add section to choose the languages
	add_settings_section(
		'easytranslate_section_lang',
		__( 'Choose languages', 'easytranslate' ),
		'easytranslate_section_lang_input',
		'easytranslate'
	);

	add_settings_field(
		'easytranslate_lang_1',
		__( 'You will write your content in this language:', 'easytranslate' ),
		'easytranslate_field_lang_1',
		'easytranslate',
		'easytranslate_section_lang',
		[
			'label_for'                 => 'easytranslate_lang_1',
			'class'                     => 'easytranslate_row',
			'easytranslate_custom_data' => 'custom',
		]
	);

	add_settings_field(
		'easytranslate_lang_2',
		__( 'Content will be translated in this language:', 'easytranslate' ),
		'easytranslate_field_lang_2',
		'easytranslate',
		'easytranslate_section_lang',
		[
			'label_for'                 => 'easytranslate_lang_2',
			'class'                     => 'easytranslate_row',
			'easytranslate_custom_data' => 'custom',
		]
	);

	// Add section to hide the Yandex Signature
	add_settings_section(
		'easytranslate_section_signature',
		__( 'Show/Hide Yandex signature', 'easytranslate' ),
		'easytranslate_section_signature_input',
		'easytranslate'
	);

	add_settings_field(
		'easytranslate_signature',
		__( 'Hide the Yandex copyright signature', 'easytranslate' ),
		'easytranslate_field_signature',
		'easytranslate',
		'easytranslate_section_signature',
		[
			'label_for'                 => 'easytranslate_signature',
			'class'                     => 'easytranslate_row',
			'easytranslate_custom_data' => 'custom',
		]
	);

}

/**
 * register our easytranslate_settings_init to the admin_init action hook
 */
add_action( 'admin_init', 'easytranslate_settings_init' );

function easytranslate_section_api_input( $args ) {
	?>
    <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'The translations are powered by Yandex. You can get your free API key', 'easytranslate' ); ?>
        <a href="https://translate.yandex.com/developers/keys"><?php esc_html_e( 'here', 'easytranslate' ); ?></a>
    </p>
	<?php
}

function easytranslate_field_api_input( $args ) {
	$options = get_option( 'easytranslate_options' );
	?>
    <input id="<?php echo esc_attr( $args['label_for'] ); ?>" type="text" placeholder="API Key"
           name="easytranslate_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
           value="<?php echo isset( $options[ $args['label_for'] ] ) ? $options[ $args['label_for'] ] : ''; ?>"
           size="80"/>
	<?php
}

function easytranslate_field_always_translate( $args ) {
	$options = get_option( 'easytranslate_options' );
	?>
    <input id="<?php echo esc_attr( $args['label_for'] ); ?>"
           type="checkbox"
           name="easytranslate_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
		<?php echo isset( $options[ $args['label_for'] ] ) ? 'checked="checked"' : ''; ?> />
	<?php
}

function easytranslate_section_lang_input() {
	?>
    <p><?php _e( 'Choose your language and the one you want to show', 'easytranslate' ) ?></p>
	<?php
}

function easytranslate_field_lang_1( $args ) {
	$options             = get_option( 'easytranslate_options' );
	$languages           = new EasyTranslate\Classes\Language( $options );
	$available_languages = $languages->getAvailableLanguages();
	$selected            = isset( $options[ $args['label_for'] ] ) ? $options[ $args['label_for'] ] : '';
	?>
    <select name="easytranslate_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
            id="<?php echo esc_attr( $args['label_for'] ); ?>">
		<?php
		foreach ( $available_languages as $key => $language ) {
			echo '<option value="' . $key . '" ' . selected( true, ( $key === $selected ), false ) . '>' . $language . '</option>';
		}
		?>
    </select>
	<?php
}

function easytranslate_field_lang_2( $args ) {
	$options             = get_option( 'easytranslate_options' );
	$languages           = new EasyTranslate\Classes\Language( $options );
	$available_languages = $languages->getAvailableLanguages();
	$selected            = isset( $options[ $args['label_for'] ] ) ? $options[ $args['label_for'] ] : '';
	?>
    <select name="easytranslate_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
            id="<?php echo esc_attr( $args['label_for'] ); ?>">
		<?php
		foreach ( $available_languages as $key => $language ) {
			echo '<option value="' . $key . '" ' . selected( true, ( $key === $selected ), false ) . '>' . $language . '</option>';
		}
		?>
    </select>
	<?php
}

function easytranslate_section_signature_input() {
	echo '<p>' . _e( 'Be careful to hide the signature only if you are using a Yandex pro plan. If you hide the Yandex signature while using their free plan, you will go against their requirements for the use of translation results.', 'easytranslate' ) . '</p>';
}

function easytranslate_field_signature( $args ) {
	$options = get_option( 'easytranslate_options' );
	?>
    <input id="<?php echo esc_attr( $args['label_for'] ); ?>"
           type="checkbox"
           name="easytranslate_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
		<?php echo isset( $options[ $args['label_for'] ] ) ? 'checked="checked"' : ''; ?> />
	<?php

}

/**
 * top level menu
 */
function easytranslate_options_page() {
	// add top level menu page
	add_menu_page(
		'Easy Translate',
		'Easy Translate',
		'manage_options',
		'easytranslate',
		'easytranslate_options_page_html'
	);
}

/**
 * register our easytranslate_options_page to the admin_menu action hook
 */
add_action( 'admin_menu', 'easytranslate_options_page' );

/**
 * top level menu:
 * callback functions
 */
function easytranslate_options_page_html() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// add error/update messages

	// check if the user have submitted the settings
	// wordpress will add the "settings-updated" $_GET parameter to the url
	if ( isset( $_GET['settings-updated'] ) ) {
		// add settings saved message with the class of "updated"
		add_settings_error( 'easytranslate_messages', 'easytranslate_message', __( 'Settings Saved', 'easytranslate' ), 'updated' );
	}

	// show error/update messages
	settings_errors( 'easytranslate_messages' );
	?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
			<?php
			settings_fields( 'easytranslate' );
			do_settings_sections( 'easytranslate' );
			submit_button( 'Save Settings' );
			?>
        </form>
    </div>
	<?php
}