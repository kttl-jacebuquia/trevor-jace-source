<?php namespace TrevorWP\Block;

use TrevorWP\Main;
use TrevorWP\Util\Log;

/**
 * Link List Block
 */
class Link_List extends Base {
	const BLOCK_NAME       = self::NAME_PREFIX . 'link-list';
	const BLOCK_NAME_CHILD = self::BLOCK_NAME . '--item';
	const CHILD_BOUNDARY   = '<!-- list-item ca9f8e50-d9b8-4095-a9f9-d0cd3952128b -->';

	/** @inheritDoc */
	public static function register() {
		$return = parent::register();

		# Register child
		register_block_type(
			static::BLOCK_NAME_CHILD,
			array(
				'render_callback' => array( static::class, 'render_child' ),
			)
		);

		return $return;
	}

	/** @inheritDoc */
	public static function render( array $attributes, string $content ): string {
		$items = explode( static::CHILD_BOUNDARY, $content );
		$items = array_filter(
			$items,
			function ( $str ) {
				return preg_match( '#\w#', $str );
			}
		);

		$count = count( $items );
		if ( $count == 0 ) {
			return '';
		}

		try {
			return Main::get_twig()->render(
				'blocks/link-list/main.twig',
				array_merge(
					$attributes,
					compact(
						'items',
					)
				)
			);
		} catch ( \Twig\Error\Error $exception ) {
			Log::error(
				'Template render error.',
				array(
					'exception'  => $exception,
					'block'      => static::BLOCK_NAME,
					'attributes' => $attributes,
					'content'    => $content,
				)
			);

			return '';
		}
	}

	/**
	 * @param array $attributes
	 * @param string $content
	 *
	 * @return string
	 */
	public static function render_child( array $attributes, string $content ): string {
		if ( empty( $attributes['title'] ) ) {
			return '';
		}

		if ( empty( $attributes['url'] ) || filter_var( $attributes['url'], FILTER_VALIDATE_URL ) === false ) {
			Log::notice( 'Link list block item has missing or invalid URL. Skipped.', compact( 'attributes' ) );

			return '';
		}

		try {
			return Main::get_twig()->render(
				'blocks/link-list/item.twig',
				array_merge( $attributes, array( 'CHILD_BOUNDARY' => static::CHILD_BOUNDARY ) )
			);
		} catch ( \Twig\Error\Error $exception ) {
			Log::error(
				'Template render error.',
				array(
					'exception'  => $exception,
					'block'      => static::BLOCK_NAME_CHILD,
					'attributes' => $attributes,
					'content'    => $content,
				)
			);

			return '';
		}
	}
}
