<?php namespace TrevorWP\Theme\Helper;

use TrevorWP\Theme\ACF\Field_Group\Page_Header;

/**
 * Main Header Helper
 */
class Main_Header {
	const DEFAULT_TEXT_COLOR = Post_Header::BG_CLR_2_TXT_CLR[ Post_Header::DEFAULT_BG_COLOR ];
	const LOGO_SPRITE_PATH   = 'static/media/logo-text-sprite.svg';

	public static $logo_view_box  = '';
	public static $logo_symbol_id = '';

	/**
	 * @return string
	 * // TODO: Maybe we should use css variables for this?
	 */
	public static function get_text_color(): string {
		$queried_object = get_queried_object();

		if ( is_singular( \TrevorWP\Util\Tools::get_public_post_types() ) && ( $queried_object ) instanceof \WP_Post ) {
			/** @var \WP_Post $queried_object */
			if ( \TrevorWP\Theme\Helper\Post_Header::supports_bg_color( $queried_object ) ) {
				list( , $txt_color ) = \TrevorWP\Theme\Helper\Post_Header::get_bg_color( $queried_object );

				return $txt_color;
			}
		} elseif ( is_page() && ! is_page_template() ) {
			$color = Page_Header::get_text_color();

			if ( ! empty( $color ) ) {
				return $color;
			}
		} elseif ( is_page_template( 'template-info-page.php' ) ) {
			return 'indigo';
		} elseif ( is_404() ) {
			return Post_Header::CLR_INDIGO;
		}

		return self::DEFAULT_TEXT_COLOR;
	}

	/**
	 * Renders the site logo sprite.
	 * This allows the logo to be used as svg xlink.
	 * Refer to static::render_logo for usage
	 */
	public static function render_logo_sprite(): string {
		$logo_path       = get_template_directory() . '/' . static::LOGO_SPRITE_PATH;
		$logo_svg_markup = file_get_contents( $logo_path );

		// Get  the logo's view box
		preg_match( "/(?<=viewBox=[\"\'])[^\"\']+/i", $logo_svg_markup, $view_box );

		// Get the logo's symbol id
		preg_match( "/(?<=symbol id=[\"\'])[^\"\']+/i", $logo_svg_markup, $symbol_id );

		static::$logo_view_box  = $view_box[0] ?? '';
		static::$logo_symbol_id = $symbol_id[0] ?? '';

		ob_start();
		?>
			<div class="logo-sprite" hidden>
				<?php echo $logo_svg_markup; ?>
			</div>
		<?php
		return ob_get_clean();
	}

	public static function render_logo( array $classnames = array() ): string {
		$classnames = array_merge(
			array( 'site-logo' ),
			$classnames,
		);

		$classnames = implode( ' ', $classnames );
		ob_start();
		?>
			<div class="<?php echo $classnames; ?>">
				<svg viewBox="<?php echo static::$logo_view_box; ?>">
					<use xlink:href="#<?php echo static::$logo_symbol_id; ?>" />
				</svg>
			</div>
		<?php
		return ob_get_clean();
	}
}
