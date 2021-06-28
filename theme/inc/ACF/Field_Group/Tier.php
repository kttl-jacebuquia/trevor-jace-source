<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\CPT\Get_Involved\Get_Involved_Object;
use TrevorWP\CPT\Get_Involved\Grant;
use TrevorWP\CPT\Get_Involved\Partner;
use TrevorWP\Meta\Taxonomy;
use TrevorWP\Theme\Helper;

class Tier extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_TITLE     = 'title';
	const FIELD_TIER_TYPE = 'tier_type';
	const FIELD_BUTTON    = 'button';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$title     = static::gen_field_key( static::FIELD_TITLE );
		$tier_type = static::gen_field_key( static::FIELD_TIER_TYPE );
		$button    = static::gen_field_key( static::FIELD_BUTTON );

		return array(
			'title'  => 'Tier',
			'fields' => array(
				static::FIELD_TITLE     => array(
					'key'      => $title,
					'name'     => static::FIELD_TITLE,
					'label'    => 'Title',
					'type'     => 'text',
					'required' => 1,
				),
				static::FIELD_TIER_TYPE => array(
					'key'           => $tier_type,
					'name'          => static::FIELD_TIER_TYPE,
					'label'         => 'Tier Type',
					'type'          => 'button_group',
					'required'      => 1,
					'choices'       => array(
						'partner' => 'Partner',
						'grant'   => 'Grant',
					),
					'default_value' => 'partner',
				),
				static::FIELD_BUTTON    => array(
					'key'           => $button,
					'name'          => static::FIELD_BUTTON,
					'label'         => 'Button',
					'type'          => 'link',
					'return_format' => 'array',
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
				'title'      => 'Tier',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$title     = static::get_val( static::FIELD_TITLE );
		$tier_type = static::get_val( static::FIELD_TIER_TYPE );
		$button    = static::get_val( static::FIELD_BUTTON );

		$taxonomy  = '';
		$post_type = '';
		$meta_key  = '';
		$tiers     = '';

		if ( 'partner' === $tier_type ) {
			$taxonomy  = Get_Involved_Object::TAXONOMY_PARTNER_TIER;
			$meta_key  = Taxonomy::KEY_PARTNER_TIER_VALUE;
			$post_type = Partner::POST_TYPE;
		} elseif ( 'grant' === $tier_type ) {
			$taxonomy  = Get_Involved_Object::TAXONOMY_GRANT_TIER;
			$meta_key  = Taxonomy::KEY_PARTNER_TIER_VALUE;
			$post_type = Grant::POST_TYPE;
		}

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
				$tier->posts = get_posts(
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
			'title'  => $title,
			'tiers'  => $tiers,
			'button' => $button,
		);

		return Helper\Tier::$tier_type( $data );
	}

	/**
	 * @inheritDoc
	 */
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		echo static::render( $post_id, null, compact( 'is_preview' ) );
	}
}
