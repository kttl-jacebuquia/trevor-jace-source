<?php namespace TrevorWP\Theme\Helper;

use TrevorWP\CPT;
use TrevorWP\Main;
use TrevorWP\Meta;
use TrevorWP\Util\Tools;

/**
 * Post Header Helper
 */
class Post_Header {
	/* Types */
	const TYPE_SPLIT = 'split';
	const TYPE_FULL = 'full';
	const TYPE_HORIZONTAL = 'horizontal';
	const TYPE_SQUARE = 'square';
	const TYPE_TEXT_ONLY = 'text_only';
	const ALL_TYPES = [
			self::TYPE_SPLIT,
			self::TYPE_FULL,
			self::TYPE_HORIZONTAL,
			self::TYPE_SQUARE,
			self::TYPE_TEXT_ONLY,
	];

	/* Colors */
	const CLR_WHITE = 'white';
	const CLR_LIGHT_GRAY = 'gray-light';
	const CLR_INDIGO = 'indigo';
	const BG_COLORS = [
			self::CLR_LIGHT_GRAY => [ 'name' => 'Light Gray', 'color' => '#F3F3F7' ],
			self::CLR_INDIGO     => [ 'name' => 'Indigo', 'color' => '#101066' ],
	];
	const BG_CLR_2_TXT_CLR = [
			self::CLR_LIGHT_GRAY => self::CLR_INDIGO,
			self::CLR_INDIGO     => self::CLR_WHITE
	];

	/* Settings */
	const SETTINGS = [
			self::TYPE_SPLIT      => [ 'name' => 'Split' ],
			self::TYPE_FULL       => [ 'name' => 'Full Bleed', 'validate' => [ 'image-horizontal' ] ],
			self::TYPE_HORIZONTAL => [ 'name' => 'Horizontal', 'validate' => [ 'image-horizontal' ] ],
			self::TYPE_SQUARE     => [ 'name' => 'Square', 'validate' => [ 'image-square' ] ],
			self::TYPE_TEXT_ONLY  => [ 'name' => 'Text Only', 'supports' => [ 'bg-color' ] ],
	];

	/* Defaults */
	const DEFAULT_TYPE = self::TYPE_TEXT_ONLY;
	const DEFAULT_BG_COLOR = self::CLR_INDIGO;

	/**
	 * Renders the post header.
	 *
	 * @param \WP_Post $post
	 * @param array $options
	 *
	 * @return string
	 */
	public static function render( \WP_Post $post, array $options = [] ): string {
		$type = $options['type'] = self::get_header_type( $post );
		$cls  = [ 'post-header', "type-${type}" ];

		# BG Color
		if ( self::supports_bg_color( $post ) ) {
			list( $bg_color, $txt_color ) = self::get_bg_color( $post );
			$cls[] = "bg-{$bg_color}"; // hint for tailwind, bg-gray-light bg-indigo text-white text-indigo // todo: move it to the tailwind config
			$cls[] = "text-{$txt_color}";
		} else {
			$cls[] = 'text-white';
		}

		$thumb_wrap_attrs = [];
		if ( $type == self::TYPE_SQUARE ) {
			$thumb_wrap_attrs['data-aspectRatio'] = '1:1';
		}

		ob_start(); ?>
		<div class="<?= esc_attr( implode( ' ', $cls ) ) ?>">
			<div class="container post-header-inner">
				<?= self::_render_content_area( $post, $options ) ?>
				<div class="thumbnail-wrap" <?= Tools::flat_attr( $thumb_wrap_attrs ) ?>>
					<?= self::_render_thumbnail( $post, $options ); ?>
				</div>
			</div>
		</div>
		<?php return ob_get_clean();
	}

	/**
	 * @param \WP_Post $post
	 * @param array $options
	 *
	 * @return string|null
	 */
	protected static function _render_thumbnail( \WP_Post $post, array &$options = [] ): ?string {
		$variants = [];
		switch ( $options['type'] ) {
			case self::TYPE_SQUARE:
				// Small
				$variants[] = Thumbnail::variant( Thumbnail::SCREEN_SM, Thumbnail::TYPE_SQUARE );
				$variants[] = Thumbnail::variant( Thumbnail::SCREEN_SM, Thumbnail::TYPE_VERTICAL );
				$variants[] = Thumbnail::variant( Thumbnail::SCREEN_SM, Thumbnail::TYPE_HORIZONTAL );
				break;
			case self::TYPE_FULL:
			case self::TYPE_HORIZONTAL:
				// Small
				$variants[] = Thumbnail::variant( Thumbnail::SCREEN_SM, Thumbnail::TYPE_VERTICAL );
				$variants[] = Thumbnail::variant( Thumbnail::SCREEN_SM, Thumbnail::TYPE_HORIZONTAL );
				$variants[] = Thumbnail::variant( Thumbnail::SCREEN_SM, Thumbnail::TYPE_SQUARE );
				// Medium
				$variants[] = Thumbnail::variant( Thumbnail::SCREEN_MD, Thumbnail::TYPE_HORIZONTAL );
				$variants[] = Thumbnail::variant( Thumbnail::SCREEN_MD, Thumbnail::TYPE_SQUARE );
				$variants[] = Thumbnail::variant( Thumbnail::SCREEN_MD, Thumbnail::TYPE_VERTICAL );
				break;
			case self::TYPE_SPLIT:
				// Small
				$variants[] = Thumbnail::variant( Thumbnail::SCREEN_SM, Thumbnail::TYPE_VERTICAL );
				$variants[] = Thumbnail::variant( Thumbnail::SCREEN_SM, Thumbnail::TYPE_SQUARE );
				$variants[] = Thumbnail::variant( Thumbnail::SCREEN_SM, Thumbnail::TYPE_HORIZONTAL );
				break;
		}

		return Thumbnail::post( $post, ...$variants );
	}

	/**
	 * @param \WP_Post $post
	 * @param array $options
	 *
	 * @return string
	 */
	protected static function _render_content_area( \WP_Post $post, array &$options = [] ): string {
		$title_top = $title_btm = $external_url = null;
		$hide_tags = false;

		# By post type
		if ( $post->post_type == CPT\RC\Guide::POST_TYPE ) {
			$title_top = 'Guide';
		} elseif ( in_array( $post->post_type, Main::BLOG_POST_TYPES ) ) {
			$title_top = 'Blog';
		} elseif ( $post->post_type == CPT\RC\External::POST_TYPE ) {
			$hide_tags    = true;
			$external_url = CPT\RC\External::obj_get_url( $post->ID );
		}

		if ( empty( $title_top ) && ! empty( $main_cat = Meta\Post::get_main_category( $post ) ) ) {
			$title_top = $main_cat->name;
		}

		/* Blogs doesn't have excerpt */
		if ( ! in_array( $post->post_type, Main::BLOG_POST_TYPES ) ) {
			$title_btm = nl2br( esc_html( $post->post_excerpt ) );
		}

		# Mid Row
		$mid_row = [];

		## Article Length
		if ( ! empty( $len_ind = Content_Length::post( $post ) ) ) {
			$mid_row[] = '<div class="length-indicator"> Article Length: ' . esc_html( $len_ind ) . "</div>";
		}

		## Sharing Box
		if ( Meta\Post::can_show_share_box( $post->ID ) ) {
			ob_start(); ?>
			<div class="sharing-box">
				<a target="_blank"
				   rel="noopener noreferrer nofollow"
				   class="post-social-share-btn"
				   href="https://www.facebook.com/sharer.php?<?= http_build_query( [ 'u' => get_permalink() ] ) ?>">
					<i class="share-icon trevor-ti-facebook"></i>
				</a>
				<a target="_blank"
				   rel="noopener noreferrer nofollow"
				   class="post-social-share-btn"
				   href="https://twitter.com/share?<?= http_build_query( [
						   'text' => get_the_title( $post ),
						   'url'  => get_permalink( $post )
				   ] ) ?>">
					<i class="share-icon trevor-ti-twitter"></i>
				</a>
				<a href="#" class="post-share-others-btn">
					<i class="share-icon trevor-ti-share-others"></i>
				</a>
			</div>
			<?php $mid_row[] = ob_get_clean();
		}

		# Tags
		$tags = $hide_tags ? [] : Taxonomy::get_post_tags_distinctive( $post, [
				'filter_count_1' => false,
				'limit'          => 0,
		] );

		ob_start(); ?>
		<div class="post-header-content">
			<?php if ( $title_top ) { ?>
				<div class="title-top"><?= $title_top ?></div>
			<?php } ?>
			<h1 class="title "><?= esc_html( $post->post_title ) ?></h1>
			<?php if ( $title_btm ) { ?>
				<div class="title-btm"><?= $title_btm ?></div>
			<?php } ?>

			<?php if ( $mid_row_count = count( $mid_row ) ) { ?>
				<div class="mid-row">
					<?php foreach ( $mid_row as $idx => $col ) { ?>
						<div class="mid-row-col">
							<?= $col ?>
						</div>
						<?= $mid_row_count - $idx > 1 ? '<div class="mid-row-v-separator"></div>' : '' ?>
					<?php } ?>
				</div>
			<?php } ?>

			<?php if ( ! empty( $tags ) ) { ?>
				<div class="tags-box">
					<?php foreach ( $tags as $tag ) { ?>
						<a href="<?= get_term_link( $tag ) ?>"
						   class="tag-box" rel="tag"><?= $tag->name ?></a>
					<?php } ?>
				</div>
			<?php } ?>

			<?php if ( ! empty( $external_url ) ) { ?>
				<div class="link-out-wrap">
					<a class="link-out"
					   href="<?= esc_url( $external_url ) ?>"
					   rel="noopener noreferrer nofollow"
					   target="_blank">Visit Site</a>
					<i class="trevor-ti-link-out"></i>
				</div>
			<?php } ?>
		</div>
		<?php return ob_get_clean();
	}

	/**
	 * @param \WP_Post $post
	 *
	 * @return string
	 */
	public static function get_header_type( \WP_Post $post ): string {
		$type = get_post_meta( $post->ID, Meta\Post::KEY_HEADER_TYPE, true );

		if ( empty( $type ) || ! in_array( $type, self::ALL_TYPES ) ) {
			$type = self::DEFAULT_TYPE;
		}

		return $type;
	}

	/**
	 * @param \WP_Post $post
	 *
	 * @return array [$bg_color, $text_color]
	 */
	public static function get_bg_color( \WP_Post $post ): array {
		$bg_color = get_post_meta( $post->ID, Meta\Post::KEY_HEADER_BG_CLR, true );

		# Fallback
		if ( empty( $bg_color ) || ! array_key_exists( $bg_color, self::BG_COLORS ) ) {
			$bg_color = self::DEFAULT_BG_COLOR;
		}

		$txt_color = self::BG_CLR_2_TXT_CLR[ $bg_color ];

		return [ $bg_color, $txt_color ];
	}

	/**
	 * @param \WP_Post $post
	 *
	 * @return bool
	 */
	public static function supports_bg_color( \WP_Post $post ): bool {
		$type     = self::get_header_type( $post );
		$settings = self::SETTINGS[ $type ];

		return ! empty( $settings['supports'] ) && in_array( 'bg-color', $settings['supports'] );
	}
}
