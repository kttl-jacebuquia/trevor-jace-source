<?php namespace TrevorWP\Theme\ACF\Field_Group;

class ECT_Map extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_TITLE       = 'title';
	const FIELD_DESCRIPTION = 'description';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$title       = static::gen_field_key( static::FIELD_TITLE );
		$description = static::gen_field_key( static::FIELD_DESCRIPTION );

		return array(
			'title'  => 'Ending Conversion Therapy Map',
			'fields' => array(
				static::FIELD_TITLE       => array(
					'key'   => $title,
					'name'  => static::FIELD_TITLE,
					'label' => 'Title',
					'type'  => 'text',
				),
				static::FIELD_DESCRIPTION => array(
					'key'   => $description,
					'name'  => static::FIELD_DESCRIPTION,
					'label' => 'Description',
					'type'  => 'textarea',
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
				'title'      => 'Ending Conversion Therapy Map',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$title       = static::get_val( static::FIELD_TITLE );
		$description = static::get_val( static::FIELD_DESCRIPTION );

		ob_start();
		?>
		<div class="ect-map">
			<div class="ect-map__container">
				<div class="ect-map__content">
					<?php if ( ! empty( $title ) ) : ?>
						<h2 class="ect-map__heading"><?php echo esc_html( $title ); ?></h2>
					<?php endif; ?>

					<?php if ( ! empty( $description ) ) : ?>
						<div class="ect-map__description"><?php echo esc_html( $description ); ?></div>
					<?php endif; ?>
					<form class="ect-map__search">
						<input type="search" class="ect-map__search-input" name="ect_map_search" />
						<button class="ect-map__search-submit trevor-ti-search" aria-label="click to filter map"></button>
					</form>
				</div>
				<div class="ect-map__map-container">
					<div class="ect-map__map" id="container">
						<div class="w-full h-full flex justify-center items-center">
							<div class="text-blue_green">Loading data...</div>
						</div>
					</div>
					<div class="button ect-map__download" type="button" aria-label="click to download this map">
						<span aria-hidden="true">Download Map <i class="trevor-ti-download"></i></span>
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
