<?php namespace TrevorWP\Theme\ACF\Field_Group;

class Charity_Navigator extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_TITLE                = 'title';
	const FIELD_DESCRIPTION          = 'description';
	const FIELD_IMAGE                = 'image';
	const FIELD_NAVIGATOR_ENTRIES    = 'navigator_entries';
	const FIELD_NAVIGATOR_ENTRY_NAME = 'navigator_entry_name';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$title                = static::gen_field_key( static::FIELD_TITLE );
		$description          = static::gen_field_key( static::FIELD_DESCRIPTION );
		$image                = static::gen_field_key( static::FIELD_IMAGE );
		$navigator_entries    = static::gen_field_key( static::FIELD_NAVIGATOR_ENTRIES );
		$navigator_entry_name = static::gen_field_key( static::FIELD_NAVIGATOR_ENTRY_NAME );

		return array(
			'title'  => 'Charity Navigator',
			'fields' => array(
				static::FIELD_TITLE             => array(
					'key'     => $title,
					'name'    => static::FIELD_TITLE,
					'label'   => 'Title',
					'type'    => 'text',
					'wrapper' => array(
						'width' => '50%',
					),
				),
				static::FIELD_DESCRIPTION       => array(
					'key'     => $description,
					'name'    => static::FIELD_DESCRIPTION,
					'label'   => 'Description',
					'type'    => 'textarea',
					'wrapper' => array(
						'width' => '50%',
					),
				),
				static::FIELD_IMAGE             => array(
					'key'           => $image,
					'name'          => static::FIELD_IMAGE,
					'label'         => 'Image',
					'type'          => 'image',
					'return_format' => 'array',
					'preview_size'  => 'thumbnail',
					'library'       => 'all',
				),
				static::FIELD_NAVIGATOR_ENTRIES => array(
					'key'        => $navigator_entries,
					'name'       => static::FIELD_NAVIGATOR_ENTRIES,
					'label'      => 'Navigator Entries',
					'type'       => 'repeater',
					'required'   => true,
					'layout'     => 'block',
					'sub_fields' => array(
						static::FIELD_NAVIGATOR_ENTRY_NAME => array(
							'key'   => $navigator_entry_name,
							'name'  => static::FIELD_NAVIGATOR_ENTRY_NAME,
							'label' => 'Name',
							'type'  => 'text',
						),
					),
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
				'title'      => 'Charity Navigator',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$title             = static::get_val( static::FIELD_TITLE );
		$description       = static::get_val( static::FIELD_DESCRIPTION );
		$image             = static::get_val( static::FIELD_IMAGE );
		$navigator_entries = static::get_val( static::FIELD_NAVIGATOR_ENTRIES );

		ob_start();
		?>
		<div class="charity-navigator">
			<div class="container mx-auto">
				<div class="charity-navigator--wrapper text-center">
					<h3 class="charity-navigator--heading"><?php echo $title; ?></h3>
					<p><?php echo $description; ?></p>

					<?php if ( $image ) : ?>
						<div class="charity-navigator__seal text-center mt-px50 block">
							<img aria-hidden="true" src="<?php echo $image['url']; ?>" class="block mx-auto" alt="<?php echo ( ! empty( $image['alt'] ) ) ? $image['alt'] : $title; ?>">
						</div>
					<?php endif; ?>
					<div>
						<?php if ( ! empty( $navigator_entries ) ) : ?>
							<div class="charity-navigator-container swiper-container mobile-only" id="nav-<?php echo uniqid(); ?>">
								<div class="charity-navigator-data swiper-wrapper">
									<?php foreach ( $navigator_entries as $navigator ) : ?>
										<div class="charity-navigator-data__item swiper-slide text-center" aria-hidden="true">
											<h2 class="charity-navigator-data__item--heading"><?php echo $navigator['navigator_entry_name']; ?></h2>
										</div>
									<?php endforeach; ?>
								</div>
								<div class="swiper-pagination"></div>
							</div>

							<div class="charity-navigator-container swiper-container">
								<div class="charity-navigator-data swiper-wrapper">
									<?php foreach ( $navigator_entries as $navigator ) : ?>
										<div class="charity-navigator-data__item swiper-slide text-center" aria-hidden="true">
											<h2 class="charity-navigator-data__item--heading"><?php echo $navigator['navigator_entry_name']; ?></h2>
										</div>
									<?php endforeach; ?>
								</div>
								<div class="swiper-pagination"></div>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
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
