<?php namespace TrevorWP\Block;

use TrevorWP\Main;
use TrevorWP\Util\Log;

/**
 * Bottom List Block
 */
class Bottom_List extends Base {
	const BLOCK_NAME = self::NAME_PREFIX . 'bottom-list';

	/** @inheritDoc */
	public static function render( array $attributes, string $content ): string {
		$list_empty = ! preg_match( '#\w#', strip_tags( $content ) );

		if ( $list_empty ) {
			return '';
		}

		try {
			return Main::get_twig()->render(
				'blocks/bottom-list/main.twig',
				array_merge( $attributes, compact( 'content' ) ),
			);
		} catch ( \Twig\Error\Error $exception ) {
			Log::error( 'Template render error.', [
				'exception'  => $exception,
				'block'      => static::BLOCK_NAME,
				'attributes' => $attributes,
				'content'    => $content,
			] );

			return '';
		}
	}
}
