<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Block;

class Custom_Heading extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_LEVEL       = 'level';
	const FIELD_IS_ANCHORED = 'is_anchored';
	const FIELD_TEXT        = 'text';
	const FIELD_DESCRIPTION = 'description';

	public static $rendered_anchors = array();

	/** @inheritDoc */
	public static function prepare_register_args(): array {
		$level       = static::gen_field_key( static::FIELD_LEVEL );
		$is_anchored = static::gen_field_key( static::FIELD_IS_ANCHORED );
		$text        = static::gen_field_key( static::FIELD_TEXT );
		$description = static::gen_field_key( static::FIELD_DESCRIPTION );

		$choices = array();
		foreach ( range( 2, 6 ) as $heading_level ) {
			$choices[ "h{$heading_level}" ] = "H$heading_level";
		}

		return array(
			'title'  => 'Custom Heading',
			'fields' => array(
				static::FIELD_LEVEL       => array(
					'key'           => $level,
					'name'          => static::FIELD_LEVEL,
					'type'          => 'button_group',
					'default_value' => 'h1',
					'choices'       => $choices,
				),
				static::FIELD_TEXT        => array(
					'key'  => $text,
					'name' => static::FIELD_TEXT,
					'type' => 'text',
				),
				static::FIELD_IS_ANCHORED => array(
					'key'           => $is_anchored,
					'name'          => static::FIELD_IS_ANCHORED,
					'label'         => 'Enable Anchor',
					'instructions'  => 'Enabling this option allows this heading to be added into the Quick Links / Highlights Sidebar',
					'type'          => 'true_false',
					'default_value' => 0,
					'ui'            => 1,
				),
				static::FIELD_DESCRIPTION => array(
					'key'               => $description,
					'name'              => static::FIELD_DESCRIPTION,
					'label'             => 'Sidebar Description',
					'type'              => 'textarea',
					'conditional_logic' => array(
						array(
							array(
								'field'    => $is_anchored,
								'operator' => '==',
								'value'    => 1,
							),
						),
					),
				),
			),
		);
	}

	public static function get_block_args(): array {
		return array_merge(
			parent::get_block_args(),
			array(
				'name'       => static::get_key(),
				'title'      => 'Custom Heading',
				'mode'       => 'preview',
				'post_types' => array_merge(
					array( 'page' ),
					Block\Core_Heading::HIGHLIGHT_POST_TYPES,
				),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		// Force preview mode only
		echo static::render( $post_id, null, compact( 'is_preview' ) );
	}

	/** @inheritdoc */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$level       = static::get_val( static::FIELD_LEVEL );
		$is_anchored = static::get_val( static::FIELD_IS_ANCHORED );
		$text        = static::get_val( static::FIELD_TEXT );
		$description = static::get_val( static::FIELD_DESCRIPTION );

		$attrs = array();

		if ( $is_anchored ) {
			$attrs['id']                = acf_slugify( strip_tags( $text ) );
			static::$rendered_anchors[] = array(
				'anchor'      => $attrs['id'],
				'text'        => $text,
				'description' => $description,
			);
		}

		ob_start();
		?>
			<<?php echo $level; ?> <?php echo static::render_attrs( array(), $attrs ); ?>>
				<?php echo $text; ?>
			</<?php echo $level; ?>>
		<?php
		return ob_get_clean();
	}

	/** @inheritdoc */
	public static function get_all(): array {
		return static::$rendered_anchors;
	}
}
