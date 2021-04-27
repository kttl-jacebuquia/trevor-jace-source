<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\ACF\Util\Field_Val_Getter;
use TrevorWP\Theme\Helper;
use TrevorWP\Util\Tools;
use TrevorWP\CPT\Team;

class Post_Carousel extends Post_Grid {
	/** @inheritdoc */
	public static function get_block_args(): array {
		return array_merge( parent::get_block_args(), [
				'title'      => 'Post Carousel',
				'category'   => 'common',
				'icon'       => 'book-alt',
				'post_types' => [ 'page' ],
		] );
	}

	/** @inheritdoc */
	protected static function prepare_register_args(): array {
		$args   = parent::prepare_register_args();
		$fields = &$args['fields'];
		unset( $fields[ static::FIELD_NUM_COLS ] );

		// todo: add carousel specific fields here.
		return $args;
	}

	/** @inheritdoc */
	public static function render( $post = false, array $data = null, array $options = [] ): ?string {
		$val           = new Field_Val_Getter( static::class, $post, $data );
		$posts         = static::_get_posts( $val );
		$cls           = [];
		$wrapper_attrs = DOM_Attr::get_attrs_of( $val->get( static::FIELD_WRAPPER_ATTR ), $cls );
		$placeholder_img = $val->get( static::FIELD_PLACEHOLDER_IMG );
		$post_type     = $val->get( static::FIELD_QUERY_PTS );
		$source        = $val->get( static::FIELD_SOURCE );

		$class = [ 'mt-12', 'mb-0', ];

		if ( in_array( Team::POST_TYPE, $post_type ) && $source === "query" ) {
			$class[] = "post-carousel--staff";
		}

		$options = [ 'class' => implode( " ", $class ) ];
		if ( ! empty( $placeholder_img ) ) {
			$options[ 'card_options' ] = [ 'placeholder_image' => $placeholder_img ];
		}

		ob_start(); ?>
		<div <?= Tools::flat_attr( $wrapper_attrs ) ?>>
			<?= Helper\Carousel::posts( $posts, $options ) ?>
		</div>
		<?php return ob_get_clean();
	}
}
