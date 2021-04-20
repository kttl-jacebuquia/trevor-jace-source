<?php
namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\Helper;
use TrevorWP\Theme\ACF\Util\Field_Val_Getter;
use TrevorWP\CPT\Get_Involved\Partner;
use TrevorWP\CPT\Get_Involved\Grant;
use TrevorWP\CPT\Get_Involved\Get_Involved_Object;
use \TrevorWP\Meta;

class Partners extends A_Field_Group implements I_Block, I_Renderable {
    const FIELD_TIER_TYPE = 'tier_type';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$tier_type = static::gen_field_key( static::FIELD_TIER_TYPE );

		return [
			'title'   => 'Tier Type',
			'fields'  => [
					static::FIELD_TIER_TYPE => [
						'key'           => $tier_type,
						'name'          => static::FIELD_TIER_TYPE,
						'label'         => 'Tier Type',
						'type'          => 'select',
						'required'      => 1,
						'choices'       => [
							Get_Involved_Object::TAXONOMY_PARTNER_TIER => 'Partner Tier',
							Get_Involved_Object::TAXONOMY_GRANT_TIER => 'Grant Tier',
						],
						'default_value' => null,
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
				'title'      => 'Partners',
				'category'   => 'common',
				'icon'       => 'groups',
				'post_types' => [ 'page' ],
		];
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = [] ): ?string {
		$taxonomy = static::get_val( static::FIELD_TIER_TYPE );

		ob_start();
		?>
            <? if ( ! empty($taxonomy) ) : ?>
                <?php
                    $post_type = ( $taxonomy === Get_Involved_Object::TAXONOMY_GRANT_TIER ) ? Grant::POST_TYPE : Partner::POST_TYPE;

                    $partner_tier = ( new \WP_Term_Query( [
                            'taxonomy'   => $taxonomy,
                            'orderby'    => 'meta_value_num',
                            'order'      => 'DESC',
                            'hide_empty' => true,
                            'meta_key'   => \TrevorWP\Meta\Taxonomy::KEY_PARTNER_TIER_VALUE,
                            'number'     => 1,
                    ] ) )->terms;
                    $partner_tier = reset( $partner_tier );

                    
                    $partners = get_posts([
                            'tax_query' => [
                                    [
                                            'taxonomy' => $taxonomy,
                                            'terms'    => [ $partner_tier->term_id ]
                                    ]
                            ],
                            'post_type' => $post_type,
                            'orderby'   => 'title',
                            'order'     => 'ASC',
                    ]);
                ?>
                <div class="partners-block__panel">
                    <div class="partners-block__title">
                        <?php if ( $post_type === Partner::POST_TYPE ) : ?>
                            <div class="partners-block__tier-name"><?= $partner_tier->name ?></div>
                            <div class="partners-block__tier-amount">
                                <?= esc_html( get_term_meta( $partner_tier->term_id, Meta\Taxonomy::KEY_PARTNER_TIER_NAME, true ) ) ?>
                            </div>
                        <?php else : ?>
                            <?= $partner_tier->name ?>
                        <?php endif; ?>
                    </div>
                    <div class="partners-block__list partners-block__list--images">
                        <?php foreach ( $partners as $partner ): ?>
                            <?php if ( $taxonomy === Get_Involved_Object::TAXONOMY_PARTNER_TIER ): ?>
                                <?php
                                    $featured_image = get_the_post_thumbnail_url($partner->ID, \TrevorWP\Theme\Helper\Thumbnail::SIZE_MD);
                                ?>
                                <div class="partners-block__image">
                                    <div class="partners-block__img-wrap">
                                        <img src="<?= $featured_image ?>" alt="<?= $partner->post_title ?>">
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="partners-block__name">
                                    <?= $partner->post_title ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <? endif; ?>
		<?php return ob_get_clean();
	}

	/**
	 * @inheritDoc
	 */
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		echo static::render( $post_id, null, compact( 'is_preview' ) );
	}

}
