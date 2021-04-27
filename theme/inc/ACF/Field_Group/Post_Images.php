<?php

namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\CPT\Get_Involved\Partner;
use \TrevorWP\Meta\Post;

class Post_Images extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_POSTS = 'posts';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$posts = static::gen_field_key( static::FIELD_POSTS );

		return [
				'title'  => 'Posts',
				'fields' => [
						static::FIELD_POSTS => [
								'key'           => $posts,
								'name'          => static::FIELD_POSTS,
								'label'         => 'Posts',
								'type'          => 'relationship',
								'filters'       => [
										0 => 'search',
										1 => 'post_type',
								],
								'return_format' => 'id',
						],
				],
		];
	}

	/**
	 * @inheritDoc
	 */
	public static function get_block_args(): array {
		return [
				'name'       => static::get_key(),
				'title'      => 'Post Images',
				'category'   => 'common',
				'icon'       => 'format-gallery',
				'post_types' => [ 'page' ],
		];
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = [] ): ?string {
		$post_ids = static::get_val( static::FIELD_POSTS );

		ob_start();
		?>
		<div class="w-full flex flex-row flex-wrap mb-px72 mx-auto mt-px20 md:justify-center lg:px-px105 md:mt-px40">
			<?php foreach ( $post_ids as $post_id ):
				if ( has_post_thumbnail( $post_id ) ): ?>
					<div class="w-1/2 md:w-1/4 lg:w-1/4 py-2" data-aspectRatio="2:1">
						<?php $post_type = get_post_type( $post_id ); ?>
						<?php $post_url = ( $post_type === Partner::POST_TYPE || $post_type === Grant::POST_TYPE ) ? Post::get_partner_url( $post_id ) : get_post_permalink( $post_id ); ?>
						<?php $has_url = ! empty( $post_url ); ?>
						<a class="w-3/4 mx-auto flex items-center content-center"
						   rel="nofollow noreferrer noopener"
						   target="_blank"
						   href="<?= $has_url ? esc_attr( $post_url ) : '#' ?>">
							<?= wp_get_attachment_image( get_post_thumbnail_id( $post_id ), 'medium', false, [
									'class' => implode( ' ', [
											'mx-auto',
											'object-center',
											'object-contain'
									] )
							] ) ?>
						</a>
					</div>
				<?php endif;
			endforeach; ?>
		</div>
		<?php return ob_get_clean();
	}

	/**
	 * @inheritDoc
	 */
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		echo static::render( $post_id, null, compact( 'is_preview' ) );
	}

}
