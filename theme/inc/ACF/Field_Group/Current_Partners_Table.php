<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\CPT\Get_Involved\Get_Involved_Object;
use TrevorWP\CPT\Get_Involved\Grant;
use TrevorWP\CPT\Get_Involved\Partner;
use TrevorWP\Meta\Taxonomy;
use TrevorWP\Theme\Helper;

class Current_Partners_Table extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_TITLE = 'title';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$title = static::gen_field_key( static::FIELD_TITLE );

		return array(
			'title'  => 'Current Partners Table',
			'fields' => array(
				static::FIELD_TITLE => array(
					'key'      => $title,
					'name'     => static::FIELD_TITLE,
					'label'    => 'Title',
					'type'     => 'text',
					'required' => 1,
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
				'title'      => 'Current Partners Table',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$title = static::get_val( static::FIELD_TITLE );

		$tiers = '';

		$taxonomy  = Get_Involved_Object::TAXONOMY_PARTNER_TIER;
		$meta_key  = Taxonomy::KEY_PARTNER_TIER_VALUE;
		$post_type = Partner::POST_TYPE;

		if ( ! empty( $taxonomy ) ) {
			$tiers = ( new \WP_Term_Query(
				array(
					'taxonomy'   => $taxonomy,
					'orderby'    => 'meta_value_num',
					'order'      => 'DESC',
					'hide_empty' => true,
					'meta_key'   => $meta_key,
				)
			) )->terms;
		}

		if ( ! empty( $tiers ) ) {
			foreach ( $tiers as $tier ) {
				$tier->logo_size = Taxonomy::get_partner_tier_logo_size( $tier->term_id );
				$tier->posts     = get_posts(
					array(
						'numberposts' => - 1,
						'orderby'     => 'name',
						'order'       => 'ASC',
						'post_type'   => $post_type,
						'tax_query'   => array(
							array(
								'taxonomy' => $taxonomy,
								'terms'    => $tier->term_id,
							),
						),
					)
				);
			}
		}

		$data = array(
			'title' => $title,
			'tiers' => $tiers,
		);

		return Helper\Tier::partner( $data );
	}

	/**
	 * @inheritDoc
	 */
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		echo static::render( $post_id, null, compact( 'is_preview' ) );
	}
}
