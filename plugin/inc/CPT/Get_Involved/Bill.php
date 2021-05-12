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
		register_post_type(
			self::POST_TYPE,
			array(
				'labels'        => array(
					'name'          => 'Bills',
					'singular_name' => 'Bill',
					'add_new'       => 'Add New Bill',
				),
				'public'        => true,
				'hierarchical'  => false,
				'show_in_rest'  => true,
				'supports'      => array(
					'title',
					'editor',
					'custom-fields',
					'excerpt',
				),
				'has_archive'   => true,
				'rewrite'       => array(
					'slug'       => self::PERMALINK_BILL,
					'with_front' => false,
					'feeds'      => false,
				),
				'template'      => array(
					array(
						'core/columns',
						array(),
						array(
							array( 'core/column' ),
						),
					),
					array(
						'trevor/bottom-list',
						array(
							'title' => 'This bill would:',
						),
					),
				),
				'template_lock' => 'insert',
			)
		);
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
		<div class="container mx-auto text-teal-dark text-left md:px-10 lg:pl-px60 lg:pr-px120">
			<?php if ( ! empty( $bill_id ) ) { ?>
				<div class="text-px16 leading-px24 tracking-em_001 font-semibold mb-3.5 text-teal-light"><?php echo $bill_id; ?></div>
			<?php } ?>
			<h2 class="text-px24 leading-px28 tracking-em001 font-semibold mb-3 lg:text-px40 lg:leading-px48 flex flex-row items-center">
				<?php echo $post->post_title; ?>

				<span class="text-white bg-teal-dark rounded-full cursor-pointer ml-3 hidden md:inline-flex w-9 h-9 items-center justify-center">
					<button class="post-share-more-btn" aria-expanded="true">
						<i class="share-icon trevor-ti-share-others md:text-px14 lg:text-px16 flex"></i>
					</button>

				</span>
				<span class="hidden">
						<div class="post-share-more-content" data-url="<?php echo get_permalink( $post ); ?>">
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
			<div class="prose prose-teal-dark">
				<?php echo apply_filters( 'the_content', get_the_content( null, false, $post ) ); ?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
