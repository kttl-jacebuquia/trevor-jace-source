<?php

namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\ACF\Util\Field_Val_Getter;

class Embed extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_HEADING      = 'heading';
	const FIELD_EMBED_URL    = 'embed_url';
	const FIELD_ASPECT_RATIO = 'aspect_ratio';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$heading      = static::gen_field_key( static::FIELD_HEADING );
		$embed_url    = static::gen_field_key( static::FIELD_EMBED_URL );
		$aspect_ratio = static::gen_field_key( static::FIELD_ASPECT_RATIO );

		return array(
			'title'  => 'Embed',
			'fields' => array(
				static::FIELD_HEADING      => array(
					'key'   => $heading,
					'name'  => static::FIELD_HEADING,
					'label' => 'Heading',
					'type'  => 'text',
				),
				static::FIELD_EMBED_URL    => array(
					'key'   => $embed_url,
					'name'  => static::FIELD_EMBED_URL,
					'label' => 'Embed',
					'type'  => 'oembed',
				),
				static::FIELD_ASPECT_RATIO => array(
					'key'           => $aspect_ratio,
					'name'          => static::FIELD_ASPECT_RATIO,
					'label'         => 'Aspect Ratio',
					'type'          => 'select',
					'choices'       => array(
						''     => '',
						'9:16' => '9:16',
						'16:9' => '16:9',
						'4:3'  => '4:3',
						'3:4'  => '3:4',
						'2:1'  => '2:1',
						'1:1'  => 'Square',
					),
					'default_value' => '9:16',
				),
			),
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function get_block_args(): array {
		return array(
			'name'       => static::get_key(),
			'title'      => 'Custom Embed',
			'category'   => 'common',
			'icon'       => '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="48" height="48" role="img" aria-hidden="true" focusable="false"><path d="M20.8 10.7l-4.3-4.3-1.1 1.1 4.3 4.3c.1.1.1.3 0 .4l-4.3 4.3 1.1 1.1 4.3-4.3c.7-.8.7-1.9 0-2.6zM4.2 11.8l4.3-4.3-1-1-4.3 4.3c-.7.7-.7 1.8 0 2.5l4.3 4.3 1.1-1.1-4.3-4.3c-.2-.1-.2-.3-.1-.4z"></path></svg>',
			'post_types' => array( 'page' ),
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$heading      = static::get_val( static::FIELD_HEADING );
		$embed        = static::get_val( static::FIELD_EMBED_URL );
		$aspect_ratio = static::get_val( static::FIELD_ASPECT_RATIO );

		# Build wrapper classnames
		$wrapper_cls   = array( 'embed flex flex-col justify-center items-center flex-nowrap' );
		$wrapper_cls[] = 'mt-px70 px-px28';
		$wrapper_cls[] = 'md:mt-px80 md:px-px50';
		$wrapper_cls[] = 'xl:mt-px120 xl:px-px240';
		$wrapper_cls   = implode( ' ', $wrapper_cls );

		# Build heading classnames
		$heading_cls   = array( 'embed-heading text-center font-bold' );
		$heading_cls[] = 'text-px32 leading-px40';
		$heading_cls[] = 'md:font-extrabold';
		$heading_cls[] = 'xl:text-px40 xl:leading-px48';
		$heading_cls   = implode( ' ', $heading_cls );

		# Build embed classnames
		$embed_cls   = array( 'embed-content w-full' );
		$embed_cls[] = 'mt-px40 rounded-px10 overflow-hidden';
		$embed_cls[] = 'xl:mt-px60 xl:tracking-em_001';
		$embed_cls   = implode( ' ', $embed_cls );

		# Build additional attributes
		$attrs        = array();
		$attrs_string = '';
		if ( ! empty( $aspect_ratio ) ) {
			$attrs['data-aspectRatio'] = $aspect_ratio;
		}
		foreach ( $attrs as $attr => $value ) {
			$attrs_string .= "{$attr}='$value'";
		}

		ob_start();
		?>
		<div class="<?php echo $wrapper_cls; ?>">
			<h2 class="<?php echo $heading_cls; ?>"><?php echo $heading; ?></h2>
			<div class="<?php echo $embed_cls; ?>" <?php echo $attrs_string; ?>>
				<?php echo $embed; ?>
			</div>
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
