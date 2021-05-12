<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\ACF\Util\Field_Val_Getter;
use TrevorWP\Theme\Helper;
use TrevorWP\Util\Tools;
use TrevorWP\CPT;

class Post_Carousel extends Post_Grid {
	/** @inheritdoc */
	public static function get_block_args(): array {
		return array_merge(
			parent::get_block_args(),
			array(
				'title'      => 'Post Carousel',
				'category'   => 'common',
				'icon'       => 'book-alt',
				'post_types' => array( 'page' ),
			)
		);
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
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$val             = new Field_Val_Getter( static::class, $post, $data );
		$source          = $val->get( static::FIELD_SOURCE );
		$cls             = array();
		$wrapper_attrs   = DOM_Attr::get_attrs_of( $val->get( static::FIELD_WRAPPER_ATTR ), $cls );
		$placeholder_img = $val->get( static::FIELD_PLACEHOLDER_IMG );
		$post_types      = ( ! empty( $val->get( static::FIELD_QUERY_PTS ) ) ) ? $val->get( static::FIELD_QUERY_PTS ) : array();
		$title           = $val->get( static::FIELD_HEADING );

		$options = array(
			'title' => $title,
		);

		if ( static::SOURCE_TOP_INDIVIDUALS === $source || static::SOURCE_TOP_TEAMS === $source ) {
			$posts            = static::_get_fundraisers( $val );
			$options['class'] = ( static::SOURCE_TOP_INDIVIDUALS === $source ) ? 'top-individuals' : 'top-teams';
		} else {
			$posts = static::_get_posts( $val );

			// Manually get each post's post type if not able to get from above
			if ( empty( $post_types ) && ! empty( $posts ) ) {
				foreach ( $posts as $post ) {
					$post_types[] = get_post_type( $post );
				}
			}

			$class     = array( 'mt-12', 'mb-0' );
			$title_cls = array();

			if ( in_array( CPT\Team::POST_TYPE, $post_types, true ) && 'query' === $source ) {
				$class[] = 'post-carousel--staff';
			} elseif ( in_array( CPT\Event::POST_TYPE, $post_types, true ) ) {
				$class[]     = 'post-carousel--event mb-px80';
				$title_cls[] = 'text-center';
			} elseif ( in_array( 'attachment', $post_types, true ) ) {
				$class[] = 'post-carousel--attachment';
			}

			$options['class']      = implode( ' ', $class );
			$options['title_cls']  = implode( ' ', $title_cls );
			$options['post_types'] = $post_types;
		}

		if ( ! empty( $placeholder_img ) ) {
			$options['card_options'] = array( 'placeholder_image' => $placeholder_img );
		}

		ob_start(); ?>
		<div <?php echo Tools::flat_attr( $wrapper_attrs ); ?>>
			<?php if ( static::SOURCE_TOP_INDIVIDUALS === $source || static::SOURCE_TOP_TEAMS === $source ) : ?>
				<?php echo Helper\Carousel::fundraisers( $posts, $options ); ?>
			<?php else : ?>
				<?php echo Helper\Carousel::posts( $posts, $options ); ?>
			<?php endif; ?>
		</div>
		<?php
		return ob_get_clean();
	}
}
