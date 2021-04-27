<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\ACF\Util\Field_Val_Getter;
use TrevorWP\Theme\Helper\Carousel;
use TrevorWP\Util\Tools;

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

		ob_start(); ?>
		<div <?= Tools::flat_attr( $wrapper_attrs ) ?>>
			<?= Carousel::posts( $posts, [ 'class' => 'expand-full-width mt-12 mb-0' ] ) ?>
		</div>
		<?php return ob_get_clean();
	}
}
