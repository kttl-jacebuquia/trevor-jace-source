<?php namespace TrevorWP\Theme\Helper;

use TrevorWP\CPT;
use TrevorWP\Util\Tools;
use \TrevorWP\Meta;
use \TrevorWP\Theme\Single_Page;
use TrevorWP\Theme\ACF\Field_Group;
use TrevorWP\Theme\ACF\Field_Group\DOM_Attr;
use TrevorWP\Theme\ACF\Util\Field_Val_Getter;
use TrevorWP\Theme\ACF\Field_Group\Financial_Report;
use TrevorWP\Theme\ACF\Field_Group\Product;

/**
 * Tile Grid item.
 */
class Tile {
	/**
	 * Renders a tile for post type item.
	 *
	 * @param \WP_Post $post - The post data.
	 * @param int      $key - The item key.
	 * @param array    $options - The tile options.
	 *
	 * @return string
	 */
	public static function post( \WP_Post $post, $key, array $options = array(), bool $as_array = false ) {
		$attachment_id = '';
		$footer_html   = '';

		if ( CPT\Donate\Partner_Prod::POST_TYPE === $post->post_type ) {
			$attachment    = Product::get_val( Product::FIELD_PRODUCT_IMAGE, $post->ID );
			$attachment_id = ( ! empty( $attachment ) ) ? $attachment['id'] : null;
		} elseif ( CPT\Donate\Prod_Partner::POST_TYPE === $post->post_type ) {
			$attachment_id = Meta\Post::get_store_img_id( $post->ID );
		}

		$data = array(
			'id'        => $post->ID,
			'title'     => $post->post_title,
			'desc'      => $post->post_excerpt,
			'cta_txt'   => 'Read More',
			'cta_url'   => get_permalink( $post ),
			'post_type' => $post->post_type,
		);

		if ( ! empty( $attachment_id ) ) {
			$img = wp_get_attachment_image(
				$attachment_id,
				'medium',
				false,
				array(
					'class' => implode(
						' ',
						array(
							'object-center',
							'object-cover',
							'tile-image',
						)
					),
				)
			);

			$data['img'] = $img;
		}

		if ( CPT\Get_Involved\Bill::POST_TYPE === $post->post_type ) {
			$data['title_top']           = \TrevorWP\Meta\Post::get_bill_id( $post->ID );
			$data['cta_txt']             = 'Read the Bill';
			$options['attr']['tabindex'] = '0';
		}

		if ( CPT\Get_Involved\Letter::POST_TYPE === $post->post_type ) {
			$data['cta_txt']             = 'Read the Letter';
			$options['attr']['tabindex'] = '0';
		}

		if ( CPT\Donate\Partner_Prod::POST_TYPE === $post->post_type ) {
			$partner           = Product::get_val( Product::FIELD_PRODUCT_PARTNER, $post->ID );
			$partner_id        = ( ! empty( $partner ) ) ? $partner->ID : null;
			$data['title_top'] = ( ! empty( $partner_id ) ) ? get_the_title( $partner_id ) : '';
		}

		if ( CPT\Donate\Prod_Partner::POST_TYPE === $post->post_type ) {
			$data['title_top'] = '';
		}

		if ( CPT\Donate\Prod_Partner::POST_TYPE === $post->post_type || CPT\Donate\Partner_Prod::POST_TYPE === $post->post_type ) {
			$data['cta_txt']      = 'Check It Out';
			$data['cta_url']      = Meta\Post::get_store_url( $post->ID ) | Product::get_val( Product::FIELD_PRODUCT_URL, $post->ID );
			$options['class'][]   = 'product-card';
			$options['card_type'] = 'product';
		}

		if ( in_array( $post->post_type, array( CPT\Get_Involved\Bill::POST_TYPE, CPT\Get_Involved\Letter::POST_TYPE ), true ) ) {
			$id                 = uniqid( 'post-' );
			$options['id']      = $id;
			$options['class'][] = 'bill-letter-card';
			$footer_html        = ( new \TrevorWP\Theme\Helper\Modal(
				CPT\Get_Involved\Bill::render_modal( $post ),
				array(
					'target' => "#{$id} a",
					'id'     => "{$id}-content",
					'class'  => array( 'bill-modal' ),
				)
			) )->render();
			ob_start();
			?>
				<script>jQuery(function () {
					trevorWP.features.sharingMore(
							document.querySelector('#<?php echo $id; ?>-content .post-share-more-btn'),
							document.querySelector('#<?php echo $id; ?>-content .post-share-more-content'),
							{appendTo: document.querySelector('#<?php echo $id; ?>-content')}
						);
					});
				</script>
			<?php
			$footer_html .= ob_get_clean();
		}

		if ( CPT\Research::POST_TYPE === $post->post_type ) {
			$data['cta_txt']    = 'Learn More';
			$formatted_date     = '<strong>' . strtoupper( gmdate( 'M. j, Y —', strtotime( $post->post_date ) ) ) . '</strong>';
			$data['desc']       = $formatted_date . ' ' . $data['desc'];
			$options['class'][] = 'research-card';
		}

		$card_html = self::custom( $data, $key, $options );

		if ( $as_array ) {
			return compact( 'card_html', 'footer_html' );
		} else {
			add_action(
				'wp_footer',
				function () use ( $footer_html ) {
					echo $footer_html;
				},
				10,
				0
			);
			return $card_html;
		}
	}

	/**
	 * @param array $data
	 * @param int $key
	 * @param array $options
	 *
	 * @return string
	 */
	public static function accordion( array $data, int $key, array $options = array() ): string {
		$options = array_merge(
			array_fill_keys(
				array(
					'class',
				),
				null
			),
			$options
		);

		# class
		$cls = array( 'tile-accordion-item', 'relative' );
		if ( ! empty( $options['class'] ) ) {
			$cls = array_merge( $cls, $options['class'] );
		}

		ob_start();
		?>
		<div class="js-accordion <?php echo implode( ' ', $cls ); ?>">
			<h2 class="accordion-header" id="heading-<?php echo $key; ?>">
				<button class="accordion-button text-px20 leading-px26 py-8 px-7 w-full text-left border-b border-blue_green border-opacity-20 flex justify-between items-center font-semibold md:pt-9 md:mb-px6 md:border-0 md:pb-0 md:text-px24 md:leading-px28 lg:text-px26 lg:leading-px36"
						type="button" aria-expanded="false" aria-controls="collapse-<?php echo $key; ?>">
					<?php echo $data['title']; ?>
				</button>
			</h2>
			<div id="collapse-<?php echo $key; ?>" class="accordion-collapse collapse" aria-labelledby="heading-<?php echo $key; ?>">
				<div class="accordion-body px-7 pt-5 pb-9 bg-gray-light md:bg-transparent md:pb-36 lg:pt-0">
					<p class="text-px18 leading-px28 mb-6 md:text-px16 md:leading-px24 lg:text-px18 lg:leading-px26">
						<?php echo $data['desc']; ?>
					</p>
					<div class="tile-cta-wrap">
						<a href="<?php echo $data['cta_url']; ?>" class="tile-cta font-bold text-px18 leading-px28 border-b-2 border-teal-dark lg:text-px20">
							<span><?php echo $data['cta_txt']; ?></span>
						</a>
					</div>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * @param array $data
	 * @param int $key
	 * @param array $options
	 *
	 * @return string
	 */
	public static function custom( array $data, int $key, array $options = array() ): string {
		if ( $options['accordion'] ) {
			return self::accordion( $data, $key, $options );
		}

		$options = array_merge(
			array_fill_keys(
				array(
					'class',
					'attr',
					'card_type',
					'hidden',
					'cta_cls',
				),
				null
			),
			$options
		);

		# class
		$cls = array( 'tile', 'relative' );
		if ( ! empty( $options['class'] ) ) {
			$cls = array_merge( $cls, $options['class'] );
		}

		# CTA Attributes
		$cta_attrs = array(
			'aria-label' => "click to read more about {$data['title']}",
		);

		if ( $options['hidden'] ) {
			$cls[] = 'hidden';
		}

		$attr          = (array) $options['attr'];
		$attr['class'] = implode( ' ', $cls );
		if ( ! empty( $options['id'] ) ) {
			$attr['id'] = $options['id'];
		}

		$cta_cls = array_merge(
			array( 'tile-cta stretched-link' ),
			(array) $options['cta_cls'],
		);
		$cta_cls = implode( ' ', $cta_cls );

		$attr['data-post']      = $data['id'];
		$attr['data-post-type'] = $data['post_type'];

		// Aria-label according to post_type
		switch ( $data['post_type'] ) {
			case 'trevor_prtnr_prod':
			case 'trevor_prod_prtnr':
				$cta_attrs['aria-label'] = "click to check out {$data['title']}";
				$cta_attrs['target']     = '_blank';
				break;
			case 'trevor_gi_bill':
				$cta_attrs['aria-label'] = "click here to read the bill {$data['title']}";
				break;
			case 'trevor_gi_letter':
				$cta_attrs['aria-label'] = "click here to read the letter {$data['title']}";
				break;
		}

		ob_start();
		?>
		<div <?php echo Tools::flat_attr( $attr ); ?>>
			<?php if ( in_array( 'clickable-card', $cls, true ) ) { ?>
				<a aria-hidden="true" tabindex="-1" href="<?php echo $data['cta_url']; ?>" class="card-link">&nbsp;</a>
			<?php } ?>

			<?php if ( ! empty( $options['post_data'] ) ) : ?>
				<?php
					// Remove recursion
					unset( $options['post_data']->tile_options );
				?>
				<script hidden type="application/json"><?php echo json_encode( $options['post_data'] ); ?></script>
			<?php endif; ?>

			<?php if ( 'product' === $options['card_type'] && ! empty( $data['img'] ) ) { ?>
				<?php echo $data['img']; ?>
			<?php } ?>
			<div class="tile-inner">
				<?php if ( ! empty( $data['img'] ) && 'product' !== $options['card_type'] ) { ?>
					<?php echo $data['img']; ?>
				<?php } ?>
				<?php if ( ! empty( $data['title_top'] ) ) { ?>
					<div class="tile-title-top"><?php echo $data['title_top']; ?></div>
				<?php } ?>
				<div class="tile-title"><?php echo $data['title']; ?></div>
				<?php if ( ! empty( $data['desc'] ) ) { ?>
					<div class="tile-desc"><?php echo $data['desc']; ?></div>
				<?php } ?>

				<?php if ( ! empty( $data['cta_txt'] ) ) { ?>
					<div class="tile-cta-wrap">
						<a
							href="<?php echo $data['cta_url']; ?>"
							class="<?php echo $cta_cls; ?>"
							<?php echo DOM_Attr::render_attrs( array(), $cta_attrs ); ?>>
							<span><?php echo $data['cta_txt']; ?></span>
						</a>
					</div>
				<?php } ?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * @param \WP_Post $post
	 * @param int $key
	 * @param array $options
	 *
	 * @return string
	 */
	public static function staff( \WP_Post $post, int $key, array $options = array(), bool $as_array = false ) :string {
		$_class                   = array( 'tile-staff', 'relative', 'shadow-darkGreen', 'overflow-hidden' );
		$post                     = get_post( $post );
		$name                     = get_the_title( $post );
		$val                      = new Field_Val_Getter( Field_Group\Team_Member::class, $post );
		$pronoun                  = $val->get( Field_Group\Team_Member::FIELD_PRONOUN );
		$group_terms              = get_the_terms( $post, CPT\Team::TAXONOMY_GROUP );
		$group                    = array_pop( $group_terms )->name;
		$thumbnail_variants       = array(
			self::_get_thumb_var( Thumbnail::TYPE_HORIZONTAL ),
			self::_get_thumb_var( Thumbnail::TYPE_SQUARE ),
			self::_get_thumb_var( Thumbnail::TYPE_VERTICAL ),
		);
		$modal_thumbnail_variants = array(
			self::_get_thumb_var( Thumbnail::TYPE_SQUARE ),
			self::_get_thumb_var( Thumbnail::TYPE_VERTICAL ),
			self::_get_thumb_var( Thumbnail::TYPE_HORIZONTAL ),
		);

		$thumbnail                = Thumbnail::post( $post, ...$thumbnail_variants );
		$modal_thumbnail_images   = Thumbnail::get_post_imgs( $post->ID, ...$modal_thumbnail_variants );
		$modal_thumbnail_alt      = 'Image of ' . $post->post_title;
		$modal_thumbnail          = Thumbnail::render_img_variants(
			$modal_thumbnail_images,
			array(
				'alt' => $modal_thumbnail_alt,
			)
		);
		$modal_thumbnail          = implode( "\n", wp_list_pluck( $modal_thumbnail, 0 ) );
		$is_placeholder_thumbnail = false;

		$options = array_merge(
			array_fill_keys(
				array(
					'placeholder_image',
					'class',
					'hidden',
				),
				array()
			),
			$options
		);

		if ( empty( $thumbnail ) ) {
			$_class[]                 = 'placeholder-thumbnail';
			$placeholder_img_id       = $options['placeholder_image'];
			$thumbnail                = wp_get_attachment_image( $placeholder_img_id );
			$is_placeholder_thumbnail = true;
		} else {
			$_class[] = 'with-thumbnail';
		}

		if ( empty( $modal_thumbnail ) ) {
			$placeholder_img_id       = $options['placeholder_image'];
			$modal_thumbnail          = wp_get_attachment_image( $placeholder_img_id );
			$is_placeholder_thumbnail = true;
		}

		// Merge all HTML classes
		$_class = array_merge( $_class, $options['class'] );

		if ( $options['hidden'] ) {
			$_class[] = 'hidden';
		}

		$id          = \uniqid( 'team-member-' );
		$footer_html = ( new \TrevorWP\Theme\Helper\Modal(
			CPT\Team::render_modal(
				$post,
				array(
					'thumbnail'                => $modal_thumbnail,
					'is_placeholder_thumbnail' => $is_placeholder_thumbnail,
				)
			),
			array(
				'target' => "#{$id} a",
				'id'     => "{$id}-content",
				'class'  => array( 'team' ),
			)
		) )->render();

		$attr          = (array) $options['attr'];
		$attr['class'] = implode( ' ', $_class );
		$attr['id']    = $id;

		$name_class = array();
		if ( strtolower( $group ) === 'founder' ) {
			$name_class    = explode( ' ', 'text-px22 leading-px28 tracking-em005 md:text-px20 md:leading-px30 xl:text-px22 xl:leading-px32' );
			$details_class = 'text-px14 leading-px18 xl:text-px16 xl:leading-px22 mt-px9 md:tracking-em005';
		} else {
			$name_class    = explode( ' ', 'text-px18 leading-px26 tracking-em005 xl:text-px22 xl:leading-px32' );
			$details_class = 'text-left text-px14 leading-px18 xl:text-px16 xl:leading-px22 mt-px9 md:tracking-em005';
		}

		ob_start();
		?>
		<div <?php echo Tools::flat_attr( $attr ); ?>>
			<a href="<?php echo get_permalink( $post ); ?>" aria-label="click here to read more aboud <?php echo esc_html( $name ); ?>">
				<div class="post-thumbnail-wrap bg-gray-light">
					<?php echo $thumbnail; ?>
				</div>
				<div class="information bg-white text-teal-dark px-4 xl:px-6 pt-4 xl:pt-6 pb-px24">
					<p class="information__name font-semibold <?php echo implode( ' ', $name_class ); ?>">
						<?php echo ( $name ); ?>
					</p>
					<?php if ( ! empty( $group ) || ! empty( $pronoun ) ) { ?>
						<div class="information__details <?php echo $details_class; ?>">
							<?php if ( ! empty( $group ) ) { ?>
								<span class="information__group font-medium pr-px12"><?php echo esc_html( $group ); ?></span>
							<?php } ?>
							<?php if ( ! empty( $pronoun ) ) { ?>
								<span class="information__pronoun font-normal pl-px12 border-l-px1 border-blue_green border-opacity-40"><?php echo esc_html( $pronoun ); ?></span>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
			</a>
		</div>
		<?php
		$card_html = ob_get_clean();

		if ( $as_array ) {
			return compact( 'card_html', 'footer_html' );
		} else {
			add_action(
				'wp_footer',
				function () use ( $footer_html ) {
					echo $footer_html;
				},
				10,
				0
			);

			return $card_html;
		}
	}

	/**
	 * @param \WP_Post $post
	 * @param int $key
	 * @param array $options
	 *
	 * @return string
	 */
	public static function financial_report( \WP_Post $post, int $key, array $options = array() ) :string {
		$options = array_merge(
			array_fill_keys(
				array(
					'class',
					'attr',
					'card_type',
					'hidden',
				),
				null
			),
			$options
		);

		# class
		$cls = array( 'tile', 'financial-report-tile', 'relative' );
		if ( ! empty( $options['class'] ) ) {
			$cls = array_merge( $cls, $options['class'] );
		}

		$reports = Financial_Report::get_reports( $post );

		$attr          = (array) $options['attr'];
		$attr['class'] = implode( ' ', $cls );

		ob_start();
		?>
		<div <?php echo Tools::flat_attr( $attr ); ?>>
			<div class="tile-inner">
				<div class="tile-title"><?php echo $post->post_title; ?></div>
				<?php if ( ! empty( $reports ) ) : ?>
					<?php foreach ( $reports as $report ) : ?>
						<div class="tile-cta-wrap">
							<a href="<?php echo $report['url']; ?>" class="tile-cta">
								<span><?php echo $report['title']; ?></span>
							</a>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>

			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * @param \WP_Post $post
	 * @param int $key
	 * @param array $options
	 *
	 * @return string
	 */
	public static function event( $post, int $key, array $options = array() ) :string {
		$post  = get_post( $post );
		$val   = new Field_Val_Getter( Field_Group\Event::class, $post );
		$date  = $val->get( Field_Group\Event::FIELD_DATE );
		$time  = $val->get( Field_Group\Event::FIELD_TIME );
		$label = $val->get( Field_Group\Event::FIELD_LABEL );
		$link  = $val->get( Field_Group\Event::FIELD_LINK );

		$options = array_merge(
			array_fill_keys(
				array(
					'class',
					'attr',
					'card_type',
					'hidden',
				),
				null
			),
			$options
		);

		# Update classname
		if ( $options['hidden'] ) {
			$options['class'][] = 'hidden';
		}

		$options = array_merge( $options, compact( 'date', 'time', 'label', 'link' ) );

		return Card::event( $post, $key, $options );
	}

	/**
	 * @param string $type
	 *
	 * @return array
	 */
	protected static function _get_thumb_var( string $type ): array {
		return Thumbnail::variant(
			Thumbnail::SCREEN_SM,
			$type,
			Thumbnail::SIZE_MD,
			array( 'class' => 'post-header-bg' )
		);
	}
}
