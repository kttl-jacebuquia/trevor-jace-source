<?php
namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\Helper;
use TrevorWP\Theme\ACF\Util\Field_Val_Getter;

class Post_Grid extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_NUM_COLS = 'number_columns';
	const FIELD_PLACEHOLDER_IMG = 'placeholder';
	const FIELD_POST_ITEMS = 'post_items';
	const FIELD_NUM_DISPLAY_LIMIT = 'number_display_limit';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$placeholder_img = static::gen_field_key( static::FIELD_PLACEHOLDER_IMG );
		$post_items = static::gen_field_key( static::FIELD_POST_ITEMS );
		$num_cols = static::gen_field_key( static::FIELD_NUM_COLS );
		$num_display_limit = static::gen_field_key( static::FIELD_NUM_DISPLAY_LIMIT );

		return [
			'title'   => 'Post Grid',
			'fields'  => [
					static::FIELD_NUM_COLS => [
						'key'           => $num_cols,
						'name'          => static::FIELD_NUM_COLS,
						'label'         => 'Number of Columns (Desktop)',
						'type'          => 'button_group',
						'required'      => 1,
						'choices'       => [
							3 => '3',
							4 => '4',
						],
						'default_value' => 4,
						'wrapper'       => [
							'width' => '50%',
							'class' => '',
							'id'    => '',
						],
					],
					static::FIELD_PLACEHOLDER_IMG => [
						'key'           => $placeholder_img,
						'name'          => static::FIELD_PLACEHOLDER_IMG,
						'label'         => 'Placeholder Image',
						'type'          => 'image',
						'required'      => 0,
						'return_format' => 'array',
						'preview_size'  => 'small',
						'library'       => 'all',
						'wrapper'       => [
							'width' => '50%',
							'class' => '',
							'id'    => '',
						],
					],
					static::FIELD_NUM_DISPLAY_LIMIT => [
						'key'           => $num_display_limit,
						'name'          => static::FIELD_NUM_DISPLAY_LIMIT,
						'label'         => 'Display Limit',
						'instructions'   => 'Number of items to be displayed before clicking <i>Load More</i>',
						'type'          => 'number',
					],
					static::FIELD_POST_ITEMS => [
						'key'           => $post_items,
						'name'          => static::FIELD_POST_ITEMS,
						'label'         => 'Posts',
						'type'          => 'relationship',
						'required'      => 1,
						'return_format' => 'object',
						'filters'           => [
							0 => 'search',
							1 => 'post_type',
							2 => 'taxonomy',
						],
						'elements'          => [
							0 => 'featured_image',
						],
					],
			],
		];
	}

	/**
	 * @inheritDoc
	 */
	public static function get_block_args(): array {
		return [
				'name'       => static::get_key(),
				'title'      => 'Post Grid',
				'category'   => 'common',
				'icon'       => 'book-alt',
				'post_types' => [ 'page' ],
		];
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = [] ): ?string {
		$num_cols = static::get_val( static::FIELD_NUM_COLS );
		$placeholder_img = static::get_val( static::FIELD_PLACEHOLDER_IMG );
		$posts = static::get_val( static::FIELD_POST_ITEMS );
		$display_limit = (int) static::get_val( static::FIELD_NUM_DISPLAY_LIMIT );
		$id = uniqid( 'tile-container-', true );

		$cls	= [ 'tile-grid-container', 'mb-px50', 'mt-px36', 'md:mt-px50', 'xl:mt-px60' ];
		if ( ! empty( $options['class'] ) ) {
			$cls	= array_merge( $cls, $options[ 'class' ] );
		}

		$tile_options = [ 'accordion' => false, 'class' => $options[ 'tileClass' ] ?: [] ];

		if ( $options['smAccordion'] ) {
			$tile_options['accordion'] = true;

			$cls[] = 'sm-accordion';
		}

		$tile_options['placeholder_image'] = $placeholder_img ? $placeholder_img[ 'ID' ] : null;

		if ( count( $posts ) < 3) {
			$cls[] = 'desktop-autofit-columns';
		} else {
			// number of columns on XL breakpoint
			$cls[] = "desktop-{$num_cols}-columns";
		}

		ob_start();
		?>
			<div class="<?php echo implode( ' ', $cls ) ?>" id="<?php echo $id; ?>">
				<?php foreach ( $posts as $key => $entry ) {
					if ( $key + 1 > $display_limit ) {
						$tile_options[ 'hidden' ] = true;
					} else {
						unset($tile_options[ 'hidden' ]);
					}

					if ( 'trevor_team' === $entry->post_type ) {
						echo Helper\Tile::staff( $entry, $key, $tile_options );
					} else {
						echo Helper\Tile::post( $entry, $key, $tile_options );
					}
				} ?>
			</div>
			<?php if ( $display_limit < count( $posts ) ) { ?>
			<div class="view-all-container text-center overflow-visible pb-2">
				<a class="view-all-cta wave-underline font-bold text-px24 leading-px34 tracking-em001 border-b-2 text-teal-dark self-center"
					href="#!" data-tile-container="<?php echo $id; ?>">
					Load More
				</a>
			</div>
			<?php } ?>
		<?php return ob_get_clean();
	}

	/**
	 * @inheritDoc
	 */
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		echo static::render( $post_id, null, compact( 'is_preview' ) );
	}

}
