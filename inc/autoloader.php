<?php

spl_autoload_register( 'easy_translate_autoloader' );

function easy_translate_autoloader( $class ) {
	$namespace = 'EasyTranslate';

	if ( strpos( $class, $namespace ) !== 0 ) {
		return;
	}

	$class = str_replace( $namespace, '', $class );
	$class = str_replace( '\\', DIRECTORY_SEPARATOR, $class ) . '.php';
	$class = substr( $class, 1 );

	$directory = plugin_dir_path( __DIR__ );
	$path      = $directory . $class;

	if ( file_exists( $path ) ) {
		require_once( $path );
	}
}

