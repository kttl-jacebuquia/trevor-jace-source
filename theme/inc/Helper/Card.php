<?php namespace TrevorWP\Theme\Helper;

use TrevorWP\Parsedown\Parsedown;
use \TrevorWP\Ranks;
use TrevorWP\CPT;
use TrevorWP\CPT\RC\RC_Object;
use TrevorWP\Meta\Post as PostMeta;

class Card {
	public static function post( $post, $key = 0, array $options = array() ): string {
		$post      = get_post( $post );
		$options   = array_merge(
			array(
				'class'            => array(), // Additional classes.
				'num_words'        => 100, // for description.
				'hide_cat_eyebrow' => false,
			),
			$options
		);
		$post_type = get_post_type( $post );
		$_class    = &$options['class'];

		// Default class.
		$_class[] = 'card-post';

		$title_top  = $title_btm = $desc = $icon_cls = null;
		$is_bg_full = false;
		$title_link = true;

		// Determine the type.
		if ( CPT\RC\Glossary::POST_TYPE === $post_type ) {
			$title_top  = 'Glossary';
			$title_btm  = $post->post_excerpt;
			$desc       = $post->post_content;
			$title_link = false;
		} elseif ( CPT\RC\External::POST_TYPE === $post_type ) {
			$title_top  = 'Resource';
			$desc       = $post->post_excerpt;
			$is_bg_full = true;
			$icon_cls   = 'trevor-ti-link-out';
		} elseif ( CPT\RC\Guide::POST_TYPE === $post_type ) {
			$title_top  = 'Guide';
			$desc       = $post->post_excerpt;
			$is_bg_full = true;
		} elseif ( CPT\Donate\Fundraiser_Stories::POST_TYPE === $post_type ) {
			$title_top = 'Fundraiser Story';
		} elseif ( CPT\RC\Article::POST_TYPE === $post_type ) {
			if ( ! $options['hide_cat_eyebrow'] ) {
				$categories = Ranks\Taxonomy::get_object_terms_ordered( $post, RC_Object::TAXONOMY_CATEGORY );
				$first_cat  = empty( $categories ) ? null : reset( $categories );
				$title_top  = $first_cat ? $first_cat->name : null;
			}

			$desc = $post->post_excerpt;

		} elseif ( in_array( $post_type, array( CPT\RC\Post::POST_TYPE ), true ) ) {
			$title_top = 'Blog';
		} elseif ( CPT\Post::POST_TYPE === $post_type ) {
			$title_top = get_the_date( 'F d, Y', $post );
			$desc 	   = $post->post_excerpt;
		}

		if ( $is_bg_full ) {
			$_class[] = 'bg-full'; // Full img bg.
		}

		// Tags.
		$tags = Taxonomy::get_post_tags_distinctive( $post, array( 'filter_count_1' => false ) );

		// Thumbnail variants.
		$thumb_var   = array( self::_get_thumb_var( Thumbnail::TYPE_VERTICAL ) );
		$thumb_var_h = self::_get_thumb_var( Thumbnail::TYPE_HORIZONTAL );

		if ( $is_bg_full ) {
			// Prefer vertical image on full bg.
			array_unshift( $thumb_var, $thumb_var_h );
		} else {
			$thumb_var[] = $thumb_var_h;
		}

		// Fallback to the square.
		$thumb_var[]   = self::_get_thumb_var( Thumbnail::TYPE_SQUARE );
		$thumb         = Thumbnail::post( $post, ...$thumb_var );
		$has_thumbnail = ! empty( $thumb );
		if ( ! $has_thumbnail ) {
			$_class[] = 'no-thumbnail';
		}

		// Process Desc.
		if ( CPT\RC\Glossary::POST_TYPE === $post_type ) {
			$desc = ( new Parsedown() )->text( wp_strip_all_tags( $desc ) );
		} else {
			$desc = esc_html( wp_trim_words( wp_strip_all_tags( $desc ), $options['num_words'] ) );
		}

		ob_start();
		?>
		<article class="<?php echo esc_attr( implode( ' ', get_post_class( $_class, $post->ID ) ) ); ?>">
			<?php if ( in_array( 'bg-full', $_class, true ) && $has_thumbnail ) { ?>
				<div class="post-thumbnail-wrap">
					<a href="<?php echo get_the_permalink( $post ); ?>">
						<?php echo $thumb; ?>
					</a>
				</div>
			<?php } ?>

			<div class="card-content">
				<div class="card-text-container relative flex flex-col flex-initial md:flex-auto">
					<?php if ( $has_thumbnail && ! in_array( 'bg-full', $_class, true ) ) { ?>
						<div class="post-thumbnail-wrap">
							<a href="<?php echo get_the_permalink( $post ); ?>">
								<?php echo $thumb; ?>
							</a>
						</div>
					<?php } ?>

					<?php if ( ! empty( $icon_cls ) ) { ?>
						<div class="icon-wrap"><i class="<?php echo esc_attr( $icon_cls ); ?>"></i></div>
					<?php } ?>

					<?php if ( ! empty( $title_top ) ) { ?>
						<div class="title-top uppercase"><?php echo $title_top; ?></div>
					<?php } ?>

					<h3 class="post-title font-semibold text-px24 leading-px28">
						<?php if ( $title_link ) { ?>
							<a href="<?php echo get_the_permalink( $post ); ?>" class="stretched-link">
								<?php echo get_the_title( $post ); ?>
							</a>
						<?php } else { ?>
							<?php echo get_the_title( $post ); ?>
						<?php } ?>
					</h3>

					<?php if ( ! empty( $title_btm ) ) { ?>
						<div class="title-btm"><?php echo esc_html( $title_btm ); ?></div>
					<?php } ?>

					<?php if ( ! empty( $desc ) ) { ?>
						<div class="post-desc"><span><?php echo $desc; /* Sanitized above */ ?></span></div>
					<?php } ?>
				</div>

				<?php if ( ! empty( $tags ) ) { ?>
					<div class="tags-box">
						<?php foreach ( $tags as $tag ) { ?>
							<a href="<?php echo esc_url( RC_Object::get_search_url( $tag->name ) ); ?>"
								class="tag-box"><?php echo $tag->name; ?></a>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
		</article>
		<?php
		return ob_get_clean();
	}

	/**
	 * @param array $data
	 * @param int   $key
	 * @param array $options
	 *
	 * @return string
	 */
	public static function fundraiser( array $data, int $key = 0, array $options = array() ): string {
		static $currency_formatter;
		$logo_url = @$data['logo_url'];
		if ( empty( $logo_url ) && ! empty( $options['placeholder_logo_id'] ) ) {
			$logo_url = wp_get_attachment_image_url( $options['placeholder_logo_id'], 'medium' );
		}

		$title           = isset( $data['title'] ) ? $data['title'] : @$data['name'];
		$total_raised    = empty( $data['total_raised'] ) ? .0 : floatval( $data['total_raised'] );
		$percent_to_goal = empty( $data['percent_to_goal'] ) ? .0 : floatval( $data['percent_to_goal'] );
		$canonical_url   = empty( $data['canonical_url'] ) ? '#' : $data['canonical_url'];
		if ( $percent_to_goal > 100.0 ) {
			$percent_to_goal = 100.0;
		}

		if ( empty( $currency_formatter ) ) {
			$currency_formatter = new \NumberFormatter( 'en-US', \NumberFormatter::CURRENCY );
			$currency_formatter->setAttribute( \NumberFormatter::MAX_FRACTION_DIGITS, 0 );
		}

		ob_start();
		?>
		<div class="card-post fundraiser">
			<div class="post-thumbnail-wrap">
				<img class="fundraiser-logo" src="<?php echo esc_url( $logo_url ); ?>" alt="Logo">
			</div>

			<div class="card-content">
				<div class="title-top uppercase">Individual donor</div>

				<h3 class="post-title font-semibold text-px24 leading-px28"
					title="<?php echo esc_attr( $clean_title = wp_filter_nohtml_kses( $title ) ); ?>">
					<a href="<?php echo esc_url( $canonical_url ); ?>"
						class="title stretched-link" target="_blank"
						rel="noopener nofollow noreferrer"><?php echo $clean_title; ?></a>
				</h3>

				<div class="fundraiser-progress-wrap">
					<div class="fundraiser-progress-bar"
						style="<?php echo sprintf( 'width: %.0f%%;', $percent_to_goal ); ?>"></div>
				</div>
				<div class="fundraiser-btm-wrap">
					<div class="fundraiser-total"><?php echo $currency_formatter->format( $total_raised ); ?> raised</div>
					<div class="fundraiser-percent"><?php echo sprintf( '%.0f%%', $percent_to_goal ); ?></div>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/*
	* @param array $post
	* @param int $key
	* @param array $options
	*
	* @return string
	*/
	public static function event( $post, int $key = 0, array $options = array() ): string {
		$post      = get_post( $post );
		$permalink = get_permalink( $post );
		$options   = array_merge(
			array(
				'class' => array(), // Additional classes
			),
			$options
		);
		$_class    = &$options['class'];

		// Default class
		$_class[] = 'card-post event';

		$title_top_segments = array(
			"<strong>{$options['date']}</strong>",
			"<span>{$options['time']}</span>",
		);
		$title_top          = implode( '', $title_top_segments );
		$title_link         = true;

		// Thumbnail variants
		$thumb_var = array(
			self::_get_thumb_var( Thumbnail::TYPE_VERTICAL ),
			self::_get_thumb_var( Thumbnail::TYPE_HORIZONTAL ),
		);

		// Fallback to the square
		$thumb_var[]   = self::_get_thumb_var( Thumbnail::TYPE_SQUARE );
		$thumb         = Thumbnail::post( $post, ...$thumb_var );
		$has_thumbnail = ! empty( $thumb );
		if ( ! $has_thumbnail ) {
			$_class[] = 'no-thumbnail';
		}

		ob_start();
		?>
		<article class="<?php echo esc_attr( implode( ' ', get_post_class( $_class, $post->ID ) ) ); ?>">
			<?php if ( ! empty( $options['label'] ) ) : ?>
				<span class="card-label">
					<?php echo $options['label']; ?>
				</span>
			<?php endif; ?>


			<?php if ( in_array( 'bg-full', $_class ) && $has_thumbnail ) { ?>
				<div class="post-thumbnail-wrap">
					<a href="<?php echo get_the_permalink( $post ); ?>">
						<?php echo $thumb; ?>
					</a>
				</div>
			<?php } ?>

			<div class="card-content">
				<div class="card-text-container relative flex flex-col flex-initial md:flex-auto">
					<?php if ( $has_thumbnail && ! in_array( 'bg-full', $_class ) ) { ?>
						<div class="post-thumbnail-wrap">
							<a href="<?php echo get_the_permalink( $post ); ?>">
								<?php echo $thumb; ?>
							</a>
						</div>
					<?php } ?>

					<?php if ( ! empty( $icon_cls ) ) { ?>
						<div class="icon-wrap"><i class="<?php echo esc_attr( $icon_cls ); ?>"></i></div>
					<?php } ?>

					<?php if ( ! empty( $title_top ) ) { ?>
						<div class="title-top"><?php echo $title_top; ?></div>
					<?php } ?>

					<h3 class="post-title">
						<?php if ( $title_link ) { ?>
							<a href="<?php echo get_the_permalink( $post ); ?>" class="stretched-link">
								<?php echo get_the_title( $post ); ?>
							</a>
						<?php } else { ?>
							<?php echo get_the_title( $post ); ?>
						<?php } ?>
					</h3>

					<?php if ( ! empty( $title_btm ) ) { ?>
						<div class="title-btm"><?php echo esc_html( $title_btm ); ?></div>
					<?php } ?>

					<?php if ( ! empty( $desc ) ) { ?>
						<div class="post-desc"><span><?php echo $desc; /* Sanitized above */ ?></span></div>
					<?php } ?>

					<?php if ( $options['show_cta'] ) : ?>
						<a href="<?php echo $permalink; ?>" class="card-cta">View Event Details</a>
					<?php endif; ?>
				</div>

				<?php if ( ! empty( $tags ) ) { ?>
					<div class="tags-box">
						<?php foreach ( $tags as $tag ) { ?>
							<a href="<?php echo esc_url( RC_Object::get_search_url( $tag->name ) ); ?>"
							class="tag-box"><?php echo $tag->name; ?></a>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
		</article>
		<?php
		return ob_get_clean();
	}

	/*
	* @param array $post
	* @param int $key
	* @param array $options
	*
	* @return string
	*/
	public static function attachment( $post, $key, $options ) {
		$is_image = wp_attachment_is_image( $post );

		$options = array_merge(
			array(
				'class' => array(), // Additional classes
			),
			$options
		);
		$_class  = &$options['class'];

		// Default class
		$_class[] = 'card-post attachment';

		// Thumbnail variants
		$thumb = wp_get_attachment_image( $post, Thumbnail::SIZE_MD );

		ob_start();
		?>
		<article class="<?php echo esc_attr( implode( ' ', get_post_class( $_class, $post->ID ) ) ); ?>">
			<div class="post-thumbnail-wrap">
				<?php echo $thumb; ?>
			</div>
		</article>
		<?php
		return ob_get_clean();
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

} ?>
