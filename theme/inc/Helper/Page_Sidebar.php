<?php namespace TrevorWP\Theme\Helper;

use TrevorWP\Theme\ACF\Field_Group\Custom_Heading;

/**
 * Page Sidebar
 */
class Page_Sidebar {

	public function __construct() {}

	/**
	 * @return string|null
	 */

	static public function render( array $options = array() ): string {
		$headings           = Custom_Heading::get_all();
		$quick_links_header = $options['heading'];

		ob_start();
		?>
			<aside class="quick-links">
				<?php if ( ! empty( $quick_links_header ) ) : ?>
					<p class="quick-links__heading"><?php echo esc_html( $quick_links_header ); ?></p>
				<?php endif; ?>
				<?php if ( ! empty( $headings ) ) : ?>
					<ul class="quick-links__list">
					<?php foreach ( $headings as $entry ) : ?>
						<li class="quick-links__item">
						<?php if ( ! empty( $entry['anchor'] ) && ! empty( $entry['text'] ) ) : ?>
							<a class="quick-links__link" href="#<?php echo $entry['anchor']; ?>"><?php echo $entry['text']; ?></a>
						</li>
						<?php endif; ?>
					<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</aside>
		<?php
		return ob_get_clean();
	}
}
