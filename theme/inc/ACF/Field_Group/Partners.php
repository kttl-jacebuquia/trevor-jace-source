<?php

namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\Helper;
use TrevorWP\Theme\ACF\Util\Field_Val_Getter;
use TrevorWP\CPT\Get_Involved\Partner;
use TrevorWP\CPT\Get_Involved\Grant;
use TrevorWP\CPT\Get_Involved\Get_Involved_Object;
use \TrevorWP\Meta;

class Partners extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_TITLE       = 'title';
	const FIELD_DESCRIPTION = 'description';
	const FIELD_BUTTON      = 'button';
	const FIELD_TIER_TYPE   = 'tier_type';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$title       = static::gen_field_key( static::FIELD_TITLE );
		$description = static::gen_field_key( static::FIELD_DESCRIPTION );
		$button      = static::gen_field_key( static::FIELD_BUTTON );
		$tier_type   = static::gen_field_key( static::FIELD_TIER_TYPE );

		return array(
			'title'  => 'Text + Table Block',
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
				static::FIELD_BUTTON      => array(
					'key'           => $button,
					'name'          => static::FIELD_BUTTON,
					'label'         => 'Button',
					'type'          => 'link',
					'return_format' => 'array',
				),
				static::FIELD_TIER_TYPE   => array(
					'key'           => $tier_type,
					'name'          => static::FIELD_TIER_TYPE,
					'label'         => 'Tier Type',
					'type'          => 'select',
					'required'      => true,
					'choices'       => array(
						Get_Involved_Object::TAXONOMY_PARTNER_TIER => 'Partner Tier',
						Get_Involved_Object::TAXONOMY_GRANT_TIER   => 'Grant Tier',
					),
					'default_value' => null,
				),
			),
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function get_block_args(): array {
		return array(
			'name'       => static::get_key(),
			'title'      => 'Text + Table Block',
			'category'   => 'common',
			'icon'       => 'groups',
			'post_types' => array( 'page' ),
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$title       = static::get_val( static::FIELD_TITLE );
		$description = static::get_val( static::FIELD_DESCRIPTION );
		$button      = static::get_val( static::FIELD_BUTTON );
		$taxonomy    = static::get_val( static::FIELD_TIER_TYPE );
		$list_type   = $taxonomy === Get_Involved_Object::TAXONOMY_PARTNER_TIER ? 'images' : 'text';

		ob_start();
		?>
		<? if ( ! empty( $taxonomy ) ) : ?>
			<?php
			$post_type = ( $taxonomy === Get_Involved_Object::TAXONOMY_GRANT_TIER ) ? Grant::POST_TYPE : Partner::POST_TYPE;

			$partner_tier = ( new \WP_Term_Query(
				array(
					'taxonomy'   => $taxonomy,
					'orderby'    => 'meta_value_num',
					'order'      => 'DESC',
					'hide_empty' => true,
					'meta_key'   => \TrevorWP\Meta\Taxonomy::KEY_PARTNER_TIER_VALUE,
					'number'     => 1,
				)
			) )->terms;
			$partner_tier = reset( $partner_tier );

			$partners = get_posts(
				array(
					'tax_query' => array(
						array(
							'taxonomy' => $taxonomy,
							'terms'    => array( $partner_tier->term_id ),
						),
					),
					'post_type' => $post_type,
					'orderby'   => 'title',
					'order'     => 'ASC',
				)
			);
			?>
			<div class="partners-block">
				<div class="partners-block__container">
					<div class="partners-block__headgroup">
						<?php if ( ! empty( $title ) ) : ?>
							<h2 class="partners-block__heading"><?php echo esc_html( $title ); ?></h2>
						<?php endif; ?>

						<?php if ( ! empty( $description ) ) : ?>
							<p class="partners-block__description"><?php echo esc_html( $description ); ?></p>
						<?php endif; ?>

						<?php if ( ! empty( $button['url'] ) ) : ?>
							<a class="partners-block__cta" href="<?php echo esc_url( $button['url'] ); ?>" target="<?php echo esc_attr( $button['target'] ); ?>"><?php echo esc_html( $button['title'] ); ?></a>
						<?php endif; ?>
					</div>
					<div class="partners-block__panel">
						<div class="partners-block__title">
							<div class="partners-block__tier-name"><?php echo $partner_tier->name; ?></div>
							<div class="partners-block__tier-amount">
								<?php echo esc_html( get_term_meta( $partner_tier->term_id, Meta\Taxonomy::KEY_PARTNER_TIER_NAME, true ) ); ?>
							</div>
						</div>
						<div class="partners-block__list partners-block__list--<?php echo $list_type; ?>">
							<?php foreach ( $partners as $partner ) : ?>
								<?php $url = Meta\Post::get_partner_url( $partner->ID ); ?>
								<?php if ( $taxonomy === Get_Involved_Object::TAXONOMY_PARTNER_TIER ) : ?>
									<?php
									$featured_image = get_the_post_thumbnail_url( $partner->ID, \TrevorWP\Theme\Helper\Thumbnail::SIZE_MD );
									?>
									<div class="partners-block__image">
										<a href="<?php echo $url; ?>"
										rel="nofollow noreferrer noopener"
										target="_blank"
										class="partners-block__img-wrap">
											<img src="<?php echo $featured_image; ?>" alt="<?php echo $partner->post_title; ?>">
										</a>
									</div>
								<?php else : ?>
									<div class="partners-block__name">
										<a href="<?php echo $url; ?>"
										rel="nofollow noreferrer noopener"
										target="_blank">
											<?php echo $partner->post_title; ?>
										</a>
									</div>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		<? endif; ?>
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
