<?php

namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\CPT\Post;
use TrevorWP\CPT\RC\Article;

class Embed extends A_Basic_Section implements I_Block, I_Renderable {
	const FIELD_EMBED_URL    = 'embed_url';
	const FIELD_ASPECT_RATIO = 'aspect_ratio';
	const FIELD_ATTR         = 'attr';

	/**
	 * @inheritDoc
	 */
	public static function prepare_register_args(): array {
		$embed_url    = static::gen_field_key( static::FIELD_EMBED_URL );
		$aspect_ratio = static::gen_field_key( static::FIELD_ASPECT_RATIO );
		$attr         = static::gen_field_key( static::FIELD_ATTR );

		return array(
			'title'  => 'Embed',
			'fields' => array(
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
						'9:21' => '9:21',
						'21:9' => '21:9',
						'9:16' => '9:16',
						'16:9' => '16:9',
						'4:3'  => '4:3',
						'3:4'  => '3:4',
						'2:1'  => '2:1',
						'1:1'  => 'Square',
					),
					'default_value' => '21:9',
				),
				static::FIELD_ATTR         => DOM_Attr::clone(
					array(
						'key'   => $attr,
						'name'  => static::FIELD_ATTR,
						'label' => 'Attributes.',
					)
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
			'post_types' => array( 'page', Article::POST_TYPE, Post::POST_TYPE ),
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$embed        = static::get_val( static::FIELD_EMBED_URL );
		$aspect_ratio = static::get_val( static::FIELD_ASPECT_RATIO );

		# Build wrapper classnames
		$wrapper_cls = array(
			'custom-embed flex justify-center items-center flex-nowrap',
		);

		# Build embed classnames
		$embed_cls   = array( 'embed-content w-full' );
		$embed_cls[] = 'overflow-hidden';
		$embed_cls[] = 'xl:tracking-em_001';
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
		<div <?php echo static::render_attrs( $wrapper_cls ); ?>>
			<div class="custom-embed__container">
				<div class="<?php echo $embed_cls; ?>" <?php echo $attrs_string; ?>>
					<?php echo $embed; ?>
				</div>
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
