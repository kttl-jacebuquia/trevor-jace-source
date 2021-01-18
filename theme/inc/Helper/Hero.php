<?php namespace TrevorWP\Theme\Helper;

/**
 * Hero Helper
 */
class Hero {
	/**
	 * Full bg image hero.
	 *
	 * @param array $imgs_data
	 * @param string $context
	 * @param array $options
	 *  - @arg array $root_cls Extra classes of the root element.
	 *  - @arg array $context_cls Extra classes of the context wrapper.
	 *  - @arg array $ext_attr Attributes for the image markup.
	 *
	 * @return string|null
	 */
	public static function img_bg( array $imgs_data, string $context, array $options = [] ): ?string {
		# Main container class
		$cls = array_merge(
				[
						'w-full',
						'py-10',
						'flex',
						'flex-col',
						'items-center',
						'relative',
						'hero',
						'hero-bg-img'
				],
				empty( $options['root_cls'] )
						? []
						: (array) $options['root_cls']
		);

		# Context Wrap class
		$context_cls = array_merge(
				[
						'container',
						'mx-auto',
						'flex',
						'flex-col',
						'items-center',
						'hero-context-wrap',
				],
				empty( $options['context_cls'] )
						? []
						: $options['context_cls']
		);

		# Image
		if ( empty( $imgs = Thumbnail::render_img_variants( $imgs_data ) ) ) {
			return null;
		}

		ob_start(); ?>
		<div class="<?= esc_attr( implode( ' ', array_unique( $cls ) ) ) ?>">
			<div class="absolute top-0 left-0 w-full h-full bg-img-wrap -z-1"><?= implode( "\n", wp_list_pluck( $imgs, 0 ) ); ?></div>
			<div class="<?= esc_attr( implode( ' ', array_unique( $context_cls ) ) ) ?>"><?= $context; ?></div>
		</div>
		<?php return ob_get_clean();
	}
}
