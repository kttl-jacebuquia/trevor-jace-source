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
	const TYPE_SPLIT      = 'split';
	const TYPE_FULL       = 'full';
	const TYPE_HORIZONTAL = 'horizontal';
	const TYPE_SQUARE     = 'square';
	const TYPE_TEXT_ONLY  = 'text_only';
	const ALL_TYPES       = array(
		self::TYPE_SPLIT,
		self::TYPE_FULL,
		self::TYPE_HORIZONTAL,
		self::TYPE_SQUARE,
		self::TYPE_TEXT_ONLY,
	);

	/* Colors */
	const CLR_WHITE        = 'white';
	const CLR_LIGHT_GRAY   = 'gray-light';
	const CLR_INDIGO       = 'indigo';
	const CLR_BLUE_GREEN   = 'blue_green';
	const CLR_TEAL_DARK    = 'teal-dark';
	const BG_COLORS        = array(
		self::CLR_LIGHT_GRAY => array(
			'name'  => 'Gray (Light)',
			'color' => '#F3F3F7',
		),
		self::CLR_INDIGO     => array(
			'name'  => 'Indigo',
			'color' => '#101066',
		),
		self::CLR_BLUE_GREEN => array(
			'name'  => 'Blue Green',
			'color' => '#005E67',
		),
		self::CLR_TEAL_DARK  => array(
			'name'  => 'Teal (Dark)',
			'color' => '#003A48',
		),
	);
	const BG_CLR_2_TXT_CLR = array(
		self::CLR_LIGHT_GRAY => self::CLR_INDIGO,
		self::CLR_INDIGO     => self::CLR_WHITE,
		self::CLR_BLUE_GREEN => self::CLR_WHITE,
		self::CLR_TEAL_DARK  => self::CLR_WHITE,
	);

	/* Settings */
	const SETTINGS = array(
		self::TYPE_SPLIT      => array(
			'name'     => 'Split',
			'supports' => array( 'bg-color' ),
		),
		self::TYPE_FULL       => array(
			'name'     => 'Full Bleed',
			'validate' => array( 'image-horizontal' ),
		),
		self::TYPE_HORIZONTAL => array(
			'name'     => 'Horizontal',
			'validate' => array( 'image-horizontal' ),
		),
		self::TYPE_SQUARE     => array(
			'name'     => 'Square',
			'validate' => array( 'image-square' ),
		),
		self::TYPE_TEXT_ONLY  => array(
			'name'     => 'Text Only',
			'supports' => array( 'bg-color' ),
		),
	);

	/* Defaults */
	const DEFAULT_TYPE     = self::TYPE_TEXT_ONLY;
	const DEFAULT_BG_COLOR = self::CLR_INDIGO;

	/**
	 * Renders the post header.
	 *
	 * @param \WP_Post $post
	 * @param array $options
	 *
	 * @return string
	 */
	public static function render( \WP_Post $post, array $options = array() ): string {
		$options['type'] = self::get_header_type( $post );
		$type            = $options['type'];
		$cls             = array( 'post-header', "type-${type}" );

		# BG Color
		if ( self::supports_bg_color( $post ) ) {
			list( $bg_color, $txt_color ) = self::get_bg_color( $post );
			$cls[]                        = "bg-{$bg_color}";
			$cls[]                        = "text-{$txt_color}";
		} else {
			$cls[] = 'text-white';
		}

		$thumb_wrap_attrs = array();
		if ( self::TYPE_SQUARE == $type ) {
			$thumb_wrap_attrs['data-aspectRatio'] = '1:1';
		}

		ob_start() ?>
		<div class="<?php echo esc_attr( implode( ' ', $cls ) ); ?>">
			<div class="container post-header-inner">
				<?php echo self::_render_content_area( $post, $options ); ?>
				<div class="thumbnail-wrap" <?php echo Tools::flat_attr( $thumb_wrap_attrs ); ?>>
					<?php echo self::_render_thumbnail( $post, $options ); ?>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * @param \WP_Post $post
	 * @param array $options
	 *
	 * @return string|null
	 */
	protected static function _render_thumbnail( \WP_Post $post, array &$options = array() ): ?string {
		$variants = array();
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
	protected static function _render_content_area( \WP_Post $post, array &$options = array() ): string {
		$external_url = null;
		$title_btm    = null;
		$title_top    = null;
		$hide_tags    = false;

		# By post type
		if ( CPT\RC\Guide::POST_TYPE == $post->post_type ) {
			$title_top = 'Guide';
		} elseif ( CPT\RC\External::POST_TYPE == $post->post_type ) {
			$hide_tags    = true;
			$external_url = CPT\RC\External::obj_get_url( $post->ID );
		} elseif ( CPT\Get_Involved\Bill::POST_TYPE == $post->post_type ) {
			$title_top = Meta\Post::get_bill_id( $post->ID );
		}

		$main_cat = Meta\Post::get_main_category( $post );
		if ( empty( $title_top ) && ! empty( $main_cat ) ) {
			$title_top = $main_cat->name;
		}

		/* Blogs doesn't have excerpt */
		if ( ! in_array( $post->post_type, Main::BLOG_POST_TYPES ) ) {
			$title_btm = nl2br( strip_tags( $post->post_excerpt /* Remove any <tilt> */ ) );
		}

		# Mid Row
		$mid_row = array();

		## Article Length
		$len_ind = Content_Length::post( $post );
		if ( ! empty( $len_ind ) ) {
			$mid_row[] = '<div class="length-indicator mid-row-text"> ' . ( CPT\RC\Guide::POST_TYPE == $post->post_type ? 'Guide' : 'Article' ) . ' Length: ' . esc_html( $len_ind ) . '</div>';
		}

		## Date Box
		if ( Meta\Post::can_show_date_box( $post->ID ) ) {
			$date_time = new \DateTime( $post->post_date );
			ob_start();
			?>

			<div class="date-box">
				<time class="mid-row-text"
					  datetime="<?php echo $post->post_date; ?>"><?php echo $date_time->format( 'M. j, Y' ); ?></time>
			</div>

			<?php
			$mid_row[] = ob_get_clean();
		}

		# Author
		if ( in_array( $post->post_type, array( CPT\Post::POST_TYPE ) ) && Meta\Post::can_show_author_box( $post->ID ) ) {
			ob_start();
			?>
			<div class="author-box mid-row-text">
				BY: <span class="author-display_name"><?php echo get_the_author_meta( 'display_name', $post->post_author ); ?></span>
			</div>
			<?php
			$mid_row[] = ob_get_clean();
		}

		## Sharing Box
		if ( Meta\Post::can_show_share_box( $post->ID ) ) {
			ob_start();

			$facebook_share_query = http_build_query( array( 'u' => get_permalink() ) );
			$twitter_share_query  = http_build_query(
				array(
					'text' => get_the_title( $post ),
					'url'  => get_permalink( $post ),
				)
			);
			?>
			<div class="sharing-box">
				<a target="_blank"
				   rel="noopener noreferrer nofollow"
				   class="post-social-share-btn"
				   data-type="facebook"
				   href="https://www.facebook.com/sharer.php?<?php echo $facebook_share_query; ?>">
					<i class="share-icon trevor-ti-facebook hover:text-melrose"></i>
				</a>
				<a target="_blank"
				   rel="noopener noreferrer nofollow"
				   class="post-social-share-btn"
				   data-type="twitter"
				   href="https://twitter.com/share?<?php echo $twitter_share_query; ?>">
					<i class="share-icon trevor-ti-twitter hover:text-melrose"></i>
				</a>
				<span>
					<a href="javascript:void(0)" role="button" class="post-share-more-btn" aria-expanded="true">
						<i class="share-icon trevor-ti-share-others hover:text-melrose"></i>
					</a>
					<span class="hidden">
						<div class="post-share-more-content">
							<div class="py-5 px-4">
								<h4 class="post-share-more-title">SHARE</h4>
								<table class="post-share-more-list">
									<tbody>
									<tr data-row="facebook">
										<td><i class="trevor-ti-facebook-alt"></i></td>
										<td>Facebook</td>
									</tr>
									<tr data-row="twitter">
										<td><i class="trevor-ti-twitter"></i></td>
										<td>Twitter</td>
									</tr>
									<tr data-row="clipboard">
										<td><i class="trevor-ti-link"></i></td>
										<td>Copy Link</td>
									</tr>
									<tr data-row="email">
										<td><i class="trevor-ti-mail"></i></td>
										<td>Email</td>
									</tr>
									</tbody>
								</table>
							</div>
						</div>
					</span>
				</span>
			</div>
			<?php
			$mid_row[] = ob_get_clean();
		}

		# Tags
		$tags = $hide_tags ? array() : Taxonomy::get_post_tags_distinctive(
			$post,
			array(
				'filter_count_1' => false,
			)
		);

		ob_start();
		?>
		<div class="post-header-content">
			<?php if ( $title_top ) { ?>
				<div class="title-top"><?php echo $title_top; ?></div>
			<?php } ?>
			<h1 class="title "><?php echo esc_html( $post->post_title ); ?></h1>
			<?php if ( $title_btm ) { ?>
				<div class="title-btm"><?php echo $title_btm; ?></div>
			<?php } ?>

			<?php
			$mid_row_count = count( $mid_row );
			if ( $mid_row_count ) {
				?>
				<div class="mid-row">
					<?php foreach ( $mid_row as $idx => $col ) { ?>
						<div class="mid-row-col">
							<?php echo $col; ?>
						</div>
						<?php echo $mid_row_count - $idx > 1 ? '<div class="mid-row-v-separator"></div>' : ''; ?>
					<?php } ?>
				</div>
			<?php } ?>

			<?php if ( ! empty( $tags ) ) { ?>
				<div class="tags-box">
					<?php foreach ( $tags as $tag ) { ?>
						<a href="<?php echo CPT\RC\RC_Object::get_search_url( $tag->name ); ?>"
						   class="tag-box"><?php echo $tag->name; ?></a>
					<?php } ?>
				</div>
			<?php } ?>

			<?php if ( ! empty( $external_url ) ) { ?>
				<div class="link-out-wrap">
					<a class="link-out"
					   href="<?php echo esc_url( $external_url ); ?>"
					   rel="noopener noreferrer nofollow"
					   target="_blank">Visit Site</a>
					<i class="trevor-ti-link-out"></i>
				</div>
			<?php } ?>
		</div>
		<?php
		return ob_get_clean();
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

		return array( $bg_color, $txt_color );
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
