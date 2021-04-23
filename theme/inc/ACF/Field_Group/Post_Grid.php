<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\CPT\Team;
use TrevorWP\Theme\Helper;
use TrevorWP\Theme\ACF\Util\Field_Val_Getter;
use TrevorWP\Util\Tools;

class Post_Grid extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_SOURCE = 'source';
	const FIELD_QUERY_PTS = 'post_query_pts'; // post types
	const FIELD_QUERY_TAXS = 'post_query_taxs'; // taxonomies
	const FIELD_POST_ITEMS = 'post_items';

	const FIELD_NUM_COLS = 'number_columns';
	const FIELD_PLACEHOLDER_IMG = 'placeholder';
	const FIELD_NUM_DISPLAY_LIMIT = 'number_display_limit';
	const FIELD_WRAPPER_ATTR = 'wrapper_attr';

	const SOURCE_QUERY = 'query';
	const SOURCE_PICK = 'pick';

	/** @inheritDoc */
	public static function register(): bool {
		add_filter( 'acf/load_field/name=' . static::FIELD_QUERY_PTS, [ static::class, 'load_field_post_query_pts' ] );

		return parent::register();
	}

	/** @inheritDoc */
	protected static function prepare_register_args(): array {
		$source            = static::gen_field_key( static::FIELD_SOURCE );
		$post_items        = static::gen_field_key( static::FIELD_POST_ITEMS );
		$q_pts             = static::gen_field_key( static::FIELD_QUERY_PTS );
		$q_taxs            = static::gen_field_key( static::FIELD_QUERY_TAXS );
		$placeholder_img   = static::gen_field_key( static::FIELD_PLACEHOLDER_IMG );
		$num_cols          = static::gen_field_key( static::FIELD_NUM_COLS );
		$num_display_limit = static::gen_field_key( static::FIELD_NUM_DISPLAY_LIMIT );
		$wrapper_attr      = static::gen_field_key( static::FIELD_WRAPPER_ATTR );

		return [
				'title'  => 'Post Grid',
				'fields' => array_merge(
						static::_gen_tab_field( 'General' ),
						[
								static::FIELD_SOURCE            => [
										'key'     => $source,
										'name'    => static::FIELD_SOURCE,
										'label'   => 'Source',
										'type'    => 'select',
										'choices' => [
												static::SOURCE_PICK  => 'Hand Pick',
												static::SOURCE_QUERY => 'Query',
										],
										'wrapper' => [
												'width' => '50%',
										],
								],
								static::FIELD_NUM_COLS          => [
										'key'           => $num_cols,
										'name'          => static::FIELD_NUM_COLS,
										'label'         => 'Number of Columns (Desktop)',
										'type'          => 'button_group',
										'required'      => true,
										'choices'       => [
												3 => '3',
												4 => '4',
										],
										'default_value' => 4,
										'wrapper'       => [
												'width' => '50%',
										],
								],
								static::FIELD_PLACEHOLDER_IMG   => [
										'key'           => $placeholder_img,
										'name'          => static::FIELD_PLACEHOLDER_IMG,
										'label'         => 'Placeholder Image',
										'type'          => 'image',
										'required'      => false,
										'return_format' => 'array',
										'preview_size'  => 'small',
										'library'       => 'all',
										'wrapper'       => [
												'width' => '50%',
										],
								],
								static::FIELD_NUM_DISPLAY_LIMIT => [
										'key'          => $num_display_limit,
										'name'         => static::FIELD_NUM_DISPLAY_LIMIT,
										'label'        => 'Display Limit',
										'instructions' => 'Number of items to be displayed before clicking <i>Load More</i>',
										'type'         => 'number',
										'wrapper'      => [
												'width' => '50%',
										],
								],
						],
						static::_gen_tab_field( 'Posts' ),
						[
								static::FIELD_QUERY_PTS  => [
										'key'               => $q_pts,
										'name'              => static::FIELD_QUERY_PTS,
										'label'             => 'Post Types',
										'type'              => 'select',
										'choices'           => [/* @see load_field_post_query_pts() */ ],
										'multiple'          => true,
										'ui'                => true,
										'conditional_logic' => [
												[
														[
																'field'    => $source,
																'operator' => '==',
																'value'    => static::SOURCE_QUERY,
														],
												]
										],
								],
								static::FIELD_QUERY_TAXS => [
										'key'               => $q_taxs,
										'name'              => static::FIELD_QUERY_TAXS,
										'label'             => 'Taxonomies',
										'type'              => 'taxonomy',
										'multiple'          => true,
										'ui'                => true,
										'conditional_logic' => [
												[
														[
																'field'    => $source,
																'operator' => '==',
																'value'    => static::SOURCE_QUERY,
														],
												]
										],
								],
								static::FIELD_POST_ITEMS => [
										'key'               => $post_items,
										'name'              => static::FIELD_POST_ITEMS,
										'label'             => 'Posts',
										'type'              => 'relationship',
										'required'          => true,
										'return_format'     => 'object',
										'filters'           => [
												0 => 'search',
												1 => 'post_type',
												2 => 'taxonomy',
										],
										'elements'          => [
												0 => 'featured_image',
										],
										'conditional_logic' => [
												[
														[
																'field'    => $source,
																'operator' => '==',
																'value'    => static::SOURCE_PICK,
														],
												]
										],
								],
						],
						static::_gen_tab_field( 'Attributes' ),
						[
								static::FIELD_WRAPPER_ATTR => DOM_Attr::clone( [
										'key'  => $wrapper_attr,
										'name' => static::FIELD_WRAPPER_ATTR,
								] )
						]
				),

		];
	}

	/** @inheritDoc */
	public static function get_block_args(): array {
		return [
				'name'       => static::get_key(),
				'title'      => 'Post Grid',
				'category'   => 'common',
				'icon'       => 'book-alt',
				'post_types' => [ 'page' ],
		];
	}

	/** @inheritDoc */
	public static function render( $post = false, array $data = null, array $options = [] ): ?string {
		$val             = new Field_Val_Getter( static::class, $post, $data );
		$num_cols        = $val->get( static::FIELD_NUM_COLS );
		$placeholder_img = $val->get( static::FIELD_PLACEHOLDER_IMG );
		$posts           = $val->get( static::FIELD_POST_ITEMS ); // todo: add query
		$display_limit   = (int) $val->get( static::FIELD_NUM_DISPLAY_LIMIT );
		$cls = [ 'tile-grid-container', 'mb-px50', 'mt-px36', 'md:mt-px50', 'xl:mt-px60', 'container', 'mx-auto' ];
		if ( ! empty( $options['class'] ) ) {
			$cls = array_merge( $cls, $options['class'] );
		}

		$tile_options = [ 'accordion' => false, 'class' => $options['tileClass'] ?: [] ];

		if ( $options['smAccordion'] ) {
			$tile_options['accordion'] = true;

			$cls[] = 'sm-accordion';
		}

		$tile_options['placeholder_image'] = $placeholder_img ?: null;

		if ( count( $posts ) < 3 ) {
			$cls[] = 'desktop-autofit-columns';
		} else {
			// number of columns on XL breakpoint
			$cls[] = "desktop-{$num_cols}-columns";
		}

		$count = 0;

		$wrapper_attrs = DOM_Attr::get_attrs_of( $val->get( static::FIELD_WRAPPER_ATTR ), $cls );

		$wrapper_attrs['id'] = $id = empty( $wrapper_attrs['id'] )
				? uniqid( 'tile-container-' )
				: $wrapper_attrs['id'];

		ob_start(); ?>
		<div <?= Tools::flat_attr( $wrapper_attrs ) ?>>
			<?php foreach ( $posts as $key => $post ) {
				$post = get_post( $post );

				$tile_options['hidden'] = $display_limit && ++ $count > $display_limit;

				switch ( get_post_type( $post ) ) {
					case Team::POST_TYPE:
						echo Helper\Tile::staff( $post, $key, $tile_options );
						break;
					// TODO: Add other post types
					default:
						echo Helper\Tile::post( $post, $key, $tile_options );
				}
			} ?>
		</div>
		<?php if ( $display_limit && $display_limit < count( $posts ) ) { ?>
			<div class="view-all-container text-center overflow-visible pb-2">
				<a class="view-all-cta wave-underline font-bold text-px24 leading-px34 tracking-em001 border-b-2 text-teal-dark self-center"
				   href="#!" data-tile-container="<?= $id; ?>">
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
		$data = (array) @$block['data'];
		echo static::render( $post_id, $data, compact( 'is_preview' ) );
	}

	/**
	 * @param $field
	 *
	 * @return mixed
	 */
	public static function load_field_post_query_pts( $field ) {
		if ( $field && ! empty( $field['key'] ) && $field['key'] == static::gen_field_key( static::FIELD_QUERY_PTS ) ) {
			$choices = [];
			foreach ( get_post_types( [], 'objects' ) as $pt ) {
				$choices[ $pt->name ] = "{$pt->name}: {$pt->label}";
			}

			$field['choices'] = $choices;
		}

		return $field;
	}
}
