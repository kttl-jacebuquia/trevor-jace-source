<?php namespace TrevorWP\Theme\Helper;

/**
 * Word Of The Day Helper
 */
class Word_Of_The_Day {
	public static function render( $options ): string {
		$options = array_merge(
			array_fill_keys(
				array(
					'word',
					'pronounciation',
					'description',
					'image',
				),
				null
			),
			$options
		);

		ob_start();
		?>
			<div class="word-of-the-day <?php echo $options['image'] ? '' : 'no-image'; ?>">
				<div class="word-of-the-day__container">
					<div class="word-of-the-day__content">
						<h2 class="word-of-the-day__eyebrow">WORD OF THE DAY</h2>
						<strong class="word-of-the-day__heading"><?php echo $options['word']; ?></strong>
						<div class="word-of-the-day__pronounciation"><?php echo nl2br( esc_html( $options['pronounciation'] ) ); ?></div>
						<p class="word-of-the-day__description">
							<?php echo nl2br( esc_html( $options['description'] ) ); ?>
						</p>
					</div>
					<?php if ( $options['image'] ) : ?>
						<div class="word-of-the-day__image">
							<img src="<?php echo $options['image']; ?>" class="word-of-the-day__img" aria-hidden="true" />
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php
		return ob_get_clean();
	}
}
