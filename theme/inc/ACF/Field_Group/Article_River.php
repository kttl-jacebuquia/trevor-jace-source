<?php namespace TrevorWP\Theme\ACF\Field_Group;

class Article_River extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_DISPLAY_LIMIT = 'display_limit';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$display_limit = static::gen_field_key( static::FIELD_DISPLAY_LIMIT );

		return array(
			'title'  => 'Article River',
			'fields' => array(
				static::FIELD_DISPLAY_LIMIT => array(
					'key'           => $display_limit,
					'name'          => static::FIELD_DISPLAY_LIMIT,
					'label'         => 'Display Limit',
					'type'          => 'number',
					'required'      => 1,
					'default_value' => 10,
					'min'           => 1,
					'step'          => 1,
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
				'title'      => 'Article River',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$display_limit = static::get_val( static::FIELD_DISPLAY_LIMIT );

		$press = get_term_by( 'slug', 'press', 'category', 'ARRAY_A' );

		$args = array(
			'numberposts' => $display_limit,
			'category'    => ! empty( $press['term_id'] ) ? $press['term_id'] : 0,
			'orderby'     => 'date',
			'order'       => 'DESC',
			'post_type'   => 'post',
		);

		$posts = get_posts( $args );

		ob_start();
		// Next Step: FE
		?>
		<div>
			<?php if ( ! empty( $posts ) ) : ?>
				<?php foreach ( $posts as $post ) : ?>
					<h1><?php echo esc_html( $post->post_title ); ?></h1>
					<p><?php echo esc_html( $post->post_excerpt ); ?></p>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
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
