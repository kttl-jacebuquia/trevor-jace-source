<?php namespace TrevorWP;

use TrevorWP\Util\Activate;

class Main {
	/*
	 * Global Keys
	 */
	const GLOBALS_PREFIX   = 'trevor__';
	const GLOBALS_VAR_MAIN = self::GLOBALS_PREFIX . 'main';
	const GLOBALS_VAR_TWIG = self::GLOBALS_PREFIX . 'twig';

	/*
	 * Option Keys
	 */
	const OPTION_KEY_PREFIX                   = 'trevor__';
	const OPTION_KEY_VERSION                  = self::OPTION_KEY_PREFIX . 'version';
	const OPTION_KEY_TREVORSPACE_ACTIVE_COUNT = self::OPTION_KEY_PREFIX . 'trevorspace_active_count';
	const OPTION_KEY_CLASSY_CLIENT_ID         = self::OPTION_KEY_PREFIX . 'classy_client_id';
	const OPTION_KEY_CLASSY_CLIENT_SECRET     = self::OPTION_KEY_PREFIX . 'classy_client_secret';
	const OPTION_KEY_COUNSELOR_LONG_WAIT      = self::OPTION_KEY_PREFIX . 'counselor_long_wait';

	/*
	 * Post Types
	 */
	const POST_TYPE_PREFIX = 'trevor_';

	/*
	 * Meta Keys
	 */
	const META_KEY_PREFIX = '_trevor_';

	/*
	 * Admin Menu Slugs
	 */
	const ADMIN_MENU_SLUG_PREFIX = 'trevor-';

	/*
	 * Query Vars
	 */
	const QV_PREFIX = 'trevor__';

	/*
	 * Cache Keys
	 */
	const CACHE_GROUP_PREFIX     = 'trevor:';
	const CACHE_GROUP_MAIN       = self::CACHE_GROUP_PREFIX . 'main';
	const CACHE_GROUP_TAX_PREFIX = self::CACHE_GROUP_PREFIX . 'tax:';

	/*
	 * Collections
	 */
	const BLOG_POST_TYPES = array(
		CPT\Post::POST_TYPE,
		CPT\RC\Post::POST_TYPE,
	);

	/**
	 * Main constructor.
	 */
	public function __construct() {
		$version = get_defined_constants()['TREVORWP_STATIC_VERSION'] ?? wp_unique_id();

		# Check for previously initiated class
		if ( self::is_initiated() ) {
			throw new Exception\Internal( 'Already initiated.' );
		}

		# Attach itself
		$GLOBALS[ self::GLOBALS_VAR_MAIN ] = $this;

		# Register hooks
		Util\Hooks::register_all();

		if ( version_compare( $old = get_option( self::OPTION_KEY_VERSION ), $version, '<' ) ) {
			Util\Log::info(
				'Looks like plugin is updated. Starting upgrading process.',
				array(
					'old' => $old,
					'new' => $version,
				)
			);

			add_action( 'init', array( Activate::class, 'activate' ), 10, 0 );
		}
	}

	/**
	 * @return bool
	 */
	static public function is_initiated() {
		$self = self::self();

		return is_object( $self ) && $self instanceof self;
	}

	/**
	 * @return Main|null
	 */
	static public function self() {
		return $GLOBALS[ self::GLOBALS_VAR_MAIN ] ?? null;
	}

	/**
	 * @return \Twig\Environment
	 */
	static public function get_twig() {
		if ( ! isset( $GLOBALS[ static::GLOBALS_VAR_TWIG ] ) ) {
			$GLOBALS[ static::GLOBALS_VAR_TWIG ] = Twig\Environment::create();
		}

		return $GLOBALS[ self::GLOBALS_VAR_TWIG ];
	}
}
