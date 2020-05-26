<?php
if ( ! function_exists( 'easytranslate_add_custom_box' ) ) {
	function easytranslate_add_custom_box() {
		add_meta_box(
			'easytranslate_box_id',
			'Easy Translate',
			'easytranslate_custom_box_html',
			'post'
		);
	}
}
add_action( 'add_meta_boxes', 'easytranslate_add_custom_box' );

function easytranslate_custom_box_html( $post ) {
	$value = get_post_meta( $post->ID, '_easytranslate_meta_key', true );
	$options = get_option( 'easytranslate_options' );
	$always_translate = (isset($options['easytranslate_always_translate']));
	?>
	<?php _e( 'Translate the content', 'easytranslate' ) ?>
    <label class="switch">
        <input type="checkbox" id="easytranslate-metabox-translate"
               name="easytranslate-metabox-translate" <?php echo ($always_translate) ? 'checked' : checked( $value ); ?> />
        <span class="slider round"></span>
    </label>
	<?php
}

/*
 * Save metabox settings
 */
if ( ! function_exists( 'easytranslate_save_postdata' ) ) {
	function easytranslate_save_postdata( $post_id ) {
		if ( array_key_exists( 'easytranslate-metabox-tranlsate', $_POST ) ) {
			update_post_meta(
				$post_id,
				'_easytranslate_meta_key',
				$_POST['easytranslate-metabox-translate']
			);
		}
	}
}
add_action( 'save_post', 'easytranslate_save_postdata' );