<?php namespace TrevorWP\Block;

use TrevorWP\Main;
use TrevorWP\Util\Log;
use TrevorWP\CPT\RC\Glossary;

/**
 * Glossary Entry Block
 */
class Glossary_Entry extends Base {
	const BLOCK_NAME = self::NAME_PREFIX . 'glossary-entry';
	const POST_TYPE  = Glossary::POST_TYPE;

	/** @inheritDoc */
	public static function render( array $attributes, string $content ): string {
		$meta = empty( $attributes['meta'] ) ? array() : $attributes['meta'];

		if ( empty( $meta['id'] ) || empty( $post = get_post( $meta['id'] ) ) ) {
			return $content; // Post is missing, just return the content.
		}

		try {
			return Main::get_twig()->render(
				'blocks/glossary-entry/main.twig',
				compact( 'post' )
			);
		} catch ( \Twig\Error\Error $exception ) {
			Log::error(
				'Template render error.',
				array(
					'exception'  => $exception,
					'block'      => self::BLOCK_NAME,
					'attributes' => $attributes,
				)
			);

			return '';
		}
	}
}
