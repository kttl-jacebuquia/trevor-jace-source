<?php namespace TrevorWP\CPT\Get_Involved;

use TrevorWP\Meta\Post;

/**
 * Advocacy: Bill
 */
class Bill extends Get_Involved_Object {
	/* Post Types */
	const POST_TYPE = self::POST_TYPE_PREFIX . 'bill';

	/** @inheritDoc */
	static function register_post_type(): void {
		# Post Type
		register_post_type( self::POST_TYPE, [
				'labels'        => [
						'name'          => 'Bills',
						'singular_name' => 'Bill',
						'add_new'       => 'Add New Bill'
				],
				'public'        => true,
				'hierarchical'  => false,
				'show_in_rest'  => true,
				'supports'      => [
						'title',
						'editor',
						'custom-fields',
						'excerpt',
				],
				'has_archive'   => true,
				'rewrite'       => [
						'slug'       => self::PERMALINK_BILL,
						'with_front' => false,
						'feeds'      => false,
				],
				'template'      => [
						[
								'core/columns',
								[],
								[
										[ 'core/column' ]
								]
						],
						[
								'trevor/bottom-list',
								[
										'title' => 'This bill would:',
								]
						]
				],
				'template_lock' => 'insert',
		] );
	}

	/**
	 * @param \WP_Post $post
	 *
	 * @return string
	 */
	public static function render_modal( \WP_Post $post ): string {
		$bill_id = ( $post->post_type == Bill::POST_TYPE ) ? Post::get_bill_id( $post->ID ) : null;

		ob_start();
		?>
		<div class="container mx-auto text-teal-dark text-left">
			<?php if ( ! empty( $bill_id ) ) { ?>
				<div class="text-px16 leading-px24 tracking-em_001 font-semibold mb-3.5 text-teal-light"><?= $bill_id ?></div>
			<?php } ?>
			<h2 class="text-px24 leading-px28 tracking-em001 font-semibold mb-3">
				<?= $post->post_title ?>

				<span class="text-white bg-teal-dark rounded-full px-2 py-1 cursor-pointer ml-3">
					<a href="#" class="post-share-more-btn" aria-expanded="true">
						<i class="share-icon trevor-ti-share-others"></i>
					</a>

				</span>
				<span class="hidden">
						<div class="post-share-more-content" data-url="<?= get_permalink( $post ) ?>">
							<div class="py-5 px-4">
								<h4 class="post-share-more-title">SHARE</h4>
								<table class="post-share-more-list">
									<tbody>
									<tr data-row="clipboard">
									    <td><i class="trevor-ti-link"></i></td>
									    <td>Copy Link</td>
									</tr>
									<tr data-row="email">
									    <td><i class="trevor-ti-mail"></i></td>
									    <td>Email</td>
									</tr>
									</tbody>
								</table>
							</div>
						</div>
					</span>
			</h2>
			<div class="prose">
				<?= apply_filters( 'the_content', get_the_content( null, false, $post ) ); ?>
			</div>
		</div>
		<?php return ob_get_clean();
	}
}
