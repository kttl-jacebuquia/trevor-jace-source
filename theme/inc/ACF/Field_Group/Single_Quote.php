<?php namespace TrevorWP\Theme\ACF\Field_Group;

class Single_Quote extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_QUOTE = 'quote';
	const FIELD_CITE  = 'cite';
	const FIELD_IMAGE = 'image';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$quote = static::gen_field_key( static::FIELD_QUOTE );
		$cite  = static::gen_field_key( static::FIELD_CITE );
		$image = static::gen_field_key( static::FIELD_IMAGE );

		return array(
			'title'  => 'Single Quote',
			'fields' => array(
				static::FIELD_QUOTE => array(
					'key'      => $quote,
					'name'     => static::FIELD_QUOTE,
					'label'    => 'Quote',
					'type'     => 'textarea',
					'required' => 1,
				),
				static::FIELD_CITE  => array(
					'key'   => $cite,
					'name'  => static::FIELD_CITE,
					'label' => 'Cite',
					'type'  => 'text',
				),
				static::FIELD_IMAGE => array(
					'key'           => $image,
					'name'          => static::FIELD_IMAGE,
					'label'         => 'Image',
					'type'          => 'image',
					'required'      => 1,
					'return_format' => 'array',
					'preview_size'  => 'thumbnail',
					'library'       => 'all',
				),
			),
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function get_block_args(): array {
		return array_merge(
			parent::get_block_args(),
			array(
				'name'       => static::get_key(),
				'title'      => 'Single Quote',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$quote = static::get_val( static::FIELD_QUOTE );
		$cite  = static::get_val( static::FIELD_CITE );
		$image = static::get_val( static::FIELD_IMAGE );

		ob_start();
		// Next Step - FE
		?>
		<div class="quote-breaker bg-gold relative">
			<figure class="container mx-auto text-left text-teal-dark">
				<div class="flex flex-row justify-start md:mb-2 lg:mb-5">
					<i class="trevor-ti-quote-open -mt-2 mr-0.5 md:text-px26 lg:text-px32 lg:mr-2"></i>
					<i class="trevor-ti-quote-close md:text-px26 lg:text-px32"></i>
				</div>
				<blockquote class="font-bold">
					<?php echo esc_html( $quote ); ?>
				</blockquote>
				<?php if ( ! empty( $cite ) ) : ?>
					<figcaption>
						<?php echo esc_html( $cite ); ?>
					</figcaption>
				<?php endif; ?>
			</figure>

			<?php if ( ! empty( $image['url'] ) ) : ?>
				<div>
					<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo ( ! empty( $image['alt'] ) ) ? esc_attr( $image['alt'] ) : esc_attr( $cite ); ?>">
				</div>
			<?php endif; ?>

		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * @inheritDoc
	 */
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		echo static::render( $post_id, null, compact( 'is_preview' ) );
	}
}
