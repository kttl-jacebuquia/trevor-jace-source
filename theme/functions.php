<?php

namespace TrevorWP\Theme {
	# Theme version
	const VERSION = '1.0.0-alpha';
}

namespace {
	# Environment
	defined( 'TREVOR_ON_DEV' ) or define( 'TREVOR_ON_DEV', false );

	# Static Folder URL
	defined( 'TREVOR_THEME_STATIC_URL' ) or define( 'TREVOR_THEME_STATIC_URL', (
		constant( 'TREVOR_ON_DEV' ) &&
		$dev_path = getenv( 'MEDIA_DEV_PATH_PREFIX' )
	)
		? trailingslashit( $dev_path ) . 'theme'
		: get_theme_file_uri( 'static' )
	);

	// Autoload
	require_once __DIR__ . '/lib/autoload.php';

	// Register all hooks
	\TrevorWP\Theme\Util\Hooks::register_all();
}
