<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\CPT\RC\RC_Object;

class Resource_Center_Block extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_TITLE       = 'title';
	const FIELD_DESCRIPTION = 'description';
	const FIELD_TOPICS      = 'topics';
	const FIELD_BUTTON      = 'button';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$title       = static::gen_field_key( static::FIELD_TITLE );
		$description = static::gen_field_key( static::FIELD_DESCRIPTION );
		$topics      = static::gen_field_key( static::FIELD_TOPICS );
		$button      = static::gen_field_key( static::FIELD_BUTTON );

		return array(
			'title'  => 'Resource Center Block',
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
				static::FIELD_TOPICS      => array(
					'key'           => $topics,
					'name'          => static::FIELD_TOPICS,
					'label'         => 'Taxonomy',
					'type'          => 'taxonomy',
					'taxonomy'      => RC_Object::TAXONOMY_TAG,
					'field_type'    => 'multi_select',
					'return_format' => 'object',
					'multiple'      => 1,
				),
				static::FIELD_BUTTON      => array(
					'key'   => $button,
					'name'  => static::FIELD_BUTTON,
					'label' => 'Button',
					'type'  => 'link',
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
				'title'      => 'Resource Center Block',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$title       = static::get_val( static::FIELD_TITLE );
		$description = static::get_val( static::FIELD_DESCRIPTION );
		$topics      = static::get_val( static::FIELD_TOPICS );
		$button      = static::get_val( static::FIELD_BUTTON );

		ob_start();
		?>
		<div class="container mx-auto">
			<?php if ( ! empty( $title ) ) : ?>
				<h3><?php echo esc_html( $title ); ?></h3>
			<?php endif; ?>

			<?php if ( ! empty( $title ) ) : ?>
				<p><?php echo esc_html( $description ); ?></p>
			<?php endif; ?>

			<?php if ( ! empty( $topics ) ) : ?>
				<?php foreach ( $topics as $topic ) : ?>
					<?php $term_link = get_term_link( $topic->term_id ); ?>
					<a href="<?php echo esc_url( $term_link ); ?>"><?php echo esc_html( $topic->name ); ?></a>
				<?php endforeach; ?>
			<?php endif; ?>

			<?php if ( ! empty( $button['url'] ) ) : ?>
				<a href="<?php echo esc_url( $button['url'] ); ?>" target="<?php echo esc_attr( $button['target'] ); ?>"><?php echo esc_html( $button['title'] ); ?></a>
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
