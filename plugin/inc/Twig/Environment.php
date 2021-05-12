<?php namespace TrevorWP\Twig;

use TrevorWP\Util\{Log, Tools};
use Twig;

/**
 * Custom Twig Environment
 */
class Environment {
	/**
	 * Creates a custom create environment.
	 *
	 * @return Twig\Environment
	 */
	static public function create() {
		global $wp_locale;

		$loader = new Twig\Loader\FilesystemLoader( array( TREVOR_PLUGIN_TEMPLATES_DIR ) );

		$env = new Twig\Environment(
			$loader,
			array(
				'debug' => WP_DEBUG,
			//          'cache' => self::get_cache_folder(),
			)
		);

		# Core Extension
		/** @var Twig\Extension\CoreExtension $core_ext */
		$core_ext = $env->getExtension( Twig\Extension\CoreExtension::class );

		# Set number format
		$core_ext->setNumberFormat( 0, $wp_locale->number_format['decimal_point'], $wp_locale->number_format['thousands_sep'] );

		# Set date format & time zone
		$core_ext->setDateFormat( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), '%d days' );
		$core_ext->setTimezone( wp_timezone() );

		# Add WP escape functions as a filter
		foreach (
			array(
				'esc_textarea',
				'esc_attr',
				'esc_url',
				// TODO: Remove unused ones
			) as $wp_esc_filter
		) {
			$env->addFilter( new Twig\TwigFilter( $wp_esc_filter, $wp_esc_filter, array( 'is_safe' => array( 'html' ) ) ) );
		}

		# Safe Functions
		foreach (
			array(
				'wp_get_attachment_image',
				'wp_trim_words',
				'get_permalink',
				'has_post_thumbnail',
				'get_the_post_thumbnail',
				'get_the_ID',
				// TODO: Remove unused ones
			) as $func
		) {
			$env->addFunction( new Twig\TwigFunction( $func, $func, array( 'is_safe' => array( 'html' ) ) ) );
		}

		# Unsafe Functions
		foreach (
			array(
				'uniqid',
			) as $func
		) {
			$env->addFunction( new Twig\TwigFunction( $func, $func ) );
		}

		# Token Parsers
		$env->addTokenParser( new Token_Parser\Footer_Print() );

		return $env;
	}

	/**
	 * Returns the cache folder.
	 *
	 * @return false|string Returns False if cannot create a cache folder.
	 */
	protected static function get_cache_folder() {
		$dir = Tools::get_cache_folder( 'twig' );

		if ( ! is_dir( $dir ) ) {
			try {
				Tools::maintain_dir( $dir, true, true );
			} catch ( \Exception $e ) {
				$dir = false;
			}
		}

		if ( ! $dir || ! is_writable( $dir ) ) {
			Log::warning( "Couldn't create a cache folder for twig.", compact( 'dir' ) );

			return false;
		}

		return $dir;
	}
}
