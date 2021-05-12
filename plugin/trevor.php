<?php
/**
 * Plugin Name: Trevor
 * Version: 1.0
 * Requires at least: 5.3
 * Requires PHP: 7.3
 * Author: Kettle
 * Author URI: https://wearekettle.com
 * Description: Required plugin for the Trevor theme.
 */

defined( 'ABSPATH' ) or exit;
defined( 'WP_DEBUG' ) or define( 'WP_DEBUG', false );
defined( 'RECOVERY_MODE_EMAIL' ) or define( 'RECOVERY_MODE_EMAIL', 'evrim.cabuk@wearekettle.com' );
defined( 'PANTHEON_PRIVATE_UPLOAD_DIR' ) or define( 'PANTHEON_PRIVATE_UPLOAD_DIR', WP_CONTENT_DIR . '/uploads/private' );
defined( 'TREVOR_PLUGIN_BASENAME' ) or define( 'TREVOR_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
defined( 'TREVOR_PLUGIN_NAME' ) or define( 'TREVOR_PLUGIN_NAME', trim( dirname( TREVOR_PLUGIN_BASENAME ), '/' ) );
defined( 'TREVOR_PLUGIN_DIR' ) or define( 'TREVOR_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . TREVOR_PLUGIN_NAME );
defined( 'TREVOR_PLUGIN_URL' ) or define( 'TREVOR_PLUGIN_URL', WP_PLUGIN_URL . '/' . TREVOR_PLUGIN_NAME );
defined( 'TREVOR_CONTENT_DIR' ) or define( 'TREVOR_CONTENT_DIR', PANTHEON_PRIVATE_UPLOAD_DIR . '/trevor' );
defined( 'TREVOR_PRIVATE_DATA_DIR' ) or define( 'TREVOR_PRIVATE_DATA_DIR', TREVOR_PLUGIN_DIR . '/private' );
defined( 'TREVOR_CACHE_DIR' ) or define( 'TREVOR_CACHE_DIR', TREVOR_CONTENT_DIR . '/cache' );
defined( 'TREVOR_LOGS_DIR' ) or define( 'TREVOR_LOGS_DIR', TREVOR_CONTENT_DIR . '/logs' );
defined( 'TREVOR_ON_DEV' ) or define( 'TREVOR_ON_DEV', constant( 'PANTHEON_ENVIRONMENT' ) == 'lando' );
defined( 'TREVOR_PLUGIN_TEMPLATES_DIR' ) or define( 'TREVOR_PLUGIN_TEMPLATES_DIR', TREVOR_PLUGIN_DIR . '/templates' );
defined( 'TREVOR_PLUGIN_STATIC_URL' ) or define(
	'TREVOR_PLUGIN_STATIC_URL',
	(
	constant( 'TREVOR_ON_DEV' ) &&
	$dev_path = getenv( 'MEDIA_DEV_PATH_PREFIX' )
	)
	? trailingslashit( $dev_path ) . 'plugin'
	: plugin_dir_url( __FILE__ ) . 'static'
);

# Plugin Autoloader
require_once( __DIR__ . '/lib/autoload.php' );

# Theme Autoloader (Try to load here first)
include_once( get_template_directory() . '/lib/autoload.php' );

# De/Activation Hooks
register_activation_hook( __FILE__, array( \TrevorWP\Util\Activate::class, 'activate' ) );
register_deactivation_hook( __FILE__, array( \TrevorWP\Util\Deactivate::class, 'deactivate' ) );

if ( ! TrevorWP\Main::is_initiated() ) {
	new TrevorWP\Main();
}
