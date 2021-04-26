<?php namespace TrevorWP\Theme\Tailwind;


class Config {
	/**
	 * @var array
	 */
	protected static $data;

	const DEFAULT_OPTION = [
		'' => 'Default',
	];

	/**
	 * @return array
	 */
	public static function get_config(): array {
		if ( is_null( static::$data ) ) {
			static::$data = json_decode( file_get_contents( get_theme_file_path( 'data/tailwind.config.json' ) ), true );
		}

		return static::$data;
	}

	/**
	 * @param array $root
	 * @param string $prefix
	 * @param int $walk
	 * @param array $collection
	 *
	 * @return array
	 */
	public static function option_walker( array $root, string $prefix = '', int $walk = 2, array &$collection = [] ): array {
		foreach ( $root as $key => $val ) {
			if ( is_array( $val ) && $walk > 0 ) {
				static::option_walker( $val, $prefix . ( ( $prefix && $key ) ? "-" : '' ) . $key, $walk - 1, $collection );
				continue;
			}

			if ( 'DEFAULT' === $key ) {
				$key = '';
			}

			$key = $prefix . ( ( $prefix && $key ) ? "-" : '' ) . $key;

			$collection[ $key ] = ( $key ? "{$key}: " : '' ) . ( is_scalar( $val ) ? $val : json_encode( $val ) );
		}

		return $collection;
	}

	/**
	 * @return array
	 */
	public static function collect_options(): array {
		$theme = static::get_config()['theme'];
		$m     = static::option_walker( $theme['margin'] );

		$options = [];

		foreach ( [ 'm', 'p' ] as $val ) {
			foreach ( [ '', 'x', 'y', 't', 'b', 'l', 'r' ] as $iter ) {
				$options[ $val . $iter ] = $m;
			}
		}

		$options['bg'] = static::option_walker( $theme['backgroundColor'] );

		$options['text']      = array_merge( static::option_walker( $theme['textColor'] ), static::option_walker( $theme['fontSize'] ) );
		$options['font']      = array_merge( static::option_walker( $theme['fontWeight'] ), static::option_walker( $theme['fontFamily'], '', 0 ) );
		$options['leading']   = static::option_walker( $theme['lineHeight'] );
		$options['tracking']  = static::option_walker( $theme['letterSpacing'] );
		$options['w']         = static::option_walker( $theme['width'] );
		$options['h']         = static::option_walker( $theme['height'] );
		$options['rounded']   = static::option_walker( $theme['borderRadius'] );
		$options['max-w']     = static::option_walker( $theme['maxWidth'] );
		$options['container'] = static::option_walker( [ '' => 'Container' ] );
		$options['border']    = array_merge( static::option_walker( $theme['borderColor'] ), static::option_walker( $theme['borderWidth'] ) );

		ksort( $options );

		return $options;
	}

	/**
	 * @return string[]
	 */
	public static function collect_screens(): array {
		return array_intersect_key(
			static::DEFAULT_OPTION + static::option_walker( static::get_config()['theme']['screens'], '', 0 ),
			array_flip( [ '', 'md', 'xl' ] )
		);
	}
}
