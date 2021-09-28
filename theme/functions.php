<?php

namespace TrevorWP\Theme {
	// Override version if provided
	$version_file = dirname( __FILE__ ) . '/build.php';
	if ( file_exists( $version_file ) ) {
		require_once $version_file;
	} else {
		define( 'TREVORWP_STATIC_VERSION', '1.0.0-alpha' ); // Default version
	}

	$GLOBALS['trevor_theme_static_ver']  = WP_DEBUG ? uniqid( TREVORWP_STATIC_VERSION . '-' ) : TREVORWP_STATIC_VERSION;
	$GLOBALS['trevor_plugin_static_ver'] = WP_DEBUG ? uniqid( TREVORWP_STATIC_VERSION . '-' ) : TREVORWP_STATIC_VERSION;
}

namespace {
	# Environment
	defined( 'TREVOR_ON_DEV' ) or define( 'TREVOR_ON_DEV', false );

	# Static Folder URL
	defined( 'TREVOR_THEME_STATIC_URL' ) or define(
		'TREVOR_THEME_STATIC_URL',
		(
		constant( 'TREVOR_ON_DEV' ) &&
		$dev_path = getenv( 'MEDIA_DEV_PATH_PREFIX' )
		)
		? trailingslashit( $dev_path ) . 'theme'
		: get_theme_file_uri( 'static' )
	);

	define( 'ALLOW_UNFILTERED_UPLOADS', true );

	// Autoload
	require_once __DIR__ . '/lib/autoload.php';

	// Register all hooks
	\TrevorWP\Theme\Util\Hooks::register_all();
}
