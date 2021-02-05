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

	/**
	 * Quote hero.
	 *
	 * @param array $data
	 * @param array $options
	 *
	 * @return string|null
	 */
	public static function quote( array $data, array $options = [] ): ?string {
		# image class
		$img_cls = empty( $options['img_class'] ) ? [
				'absolute',
				'bottom-0',
				'right-0',
				'h-3/5',
				'w-auto'
		] : (array) $options['img_class'];
		ob_start(); ?>
		<div class="hero h-px737 lg:h-px546 lg:flex lg:items-center">
			<figure class="container text-left text-teal-dark pt-10 md:flex-1 lg:p-0 lg:w-4/5 lg:flex-initial z-1">
				<div class="flex flex-row justify-start md:mb-2 lg:mb-5">
					<i class="trevor-ti-quote-open -mt-2 mr-0.5 md:text-px26 lg:text-px32 lg:mr-2"></i>
					<i class="trevor-ti-quote-close md:text-px26 lg:text-px32"></i>
				</div>
				<blockquote
						class="font-bold text-3xl my-4 md:text-px30 md:leading-px40 md:mr-24 lg:text-px32 lg:leading-px42 lg:font-semibold">
					<?= @$data['quote'] ?>
				</blockquote>
				<?php if ( ! empty( $data['cite'] ) ) { ?>
					<figcaption class="text-px18 leading-px26 lg:text-px22 lg:leading-px32">
						<?= $data['cite'] ?>
					</figcaption>
				<?php } ?>
			</figure>

			<?php
			if ( ! empty( $data['img'] ) && ! empty( $data['img']['id'] ) ) {
				echo wp_get_attachment_image( $data['img']['id'], 'medium', false, [
						'class' => implode( ' ', array_unique( $img_cls ) )
				] );
			}
			?>

		</div>
		<?php return self::img_bg( [
				[ $options['img_id'] ]
		], ob_get_clean(), $options );
	}
}
