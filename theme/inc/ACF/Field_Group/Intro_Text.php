<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\CPT\RC\Article;

class Intro_Text extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_TEXT       = 'title';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$text = static::gen_field_key( static::FIELD_TEXT );

		return array(
			'title'  => 'Intro Text',
			'fields' => array(
				static::FIELD_TEXT       => array(
					'key'   => $text,
					'name'  => static::FIELD_TEXT,
					'label' => 'Intro Text',
					'type'  => 'textarea',
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
				'title'      => 'Intro Text',
				'post_types' => array(
					'post',
					Article::POST_TYPE,
				),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$intro_text = static::get_val( static::FIELD_TEXT );

		ob_start();
		?>
		<?php if ( ! empty( $intro_text ) ) : ?>
			<p class="intro-text"><?php echo $intro_text; ?></p>
		<?php endif; ?>
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
