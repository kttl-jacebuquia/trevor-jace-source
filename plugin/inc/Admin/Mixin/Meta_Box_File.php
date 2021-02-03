<?php namespace TrevorWP\Admin\Mixin;

use TrevorWP\Meta;

/**
 * File MetaBox
 */
trait Meta_Box_File {
	/**
	 * @param \WP_Post $post
	 * @param array $args
	 * @param string $type
	 */
	public static function render_meta_box( \WP_Post $post, array $args = [], string $type = 'media' ): void {
		global $post;


		$meta_box_id = $args['id'];
		$meta_key    = Meta\Post::KEY_FILE;

		list( $field_name ) = $args['args'];
		$upload_link = esc_url( get_upload_iframe_src( $type, $post->ID ) );
		$your_img_id = get_post_meta( $post->ID, $meta_key, true );
		$url         = wp_attachment_is_image( $your_img_id ) ? wp_get_attachment_image( $your_img_id ) : esc_html( wp_get_attachment_url( $your_img_id ) );

		$has_file = ! empty( $url );
		?>

		<div class="custom-img-container">
			<?php if ( $has_file ) : ?>
				<a href="<?= esc_url( $url ) ?>" title="Download file"><?= $url ?></a>
			<?php endif; ?>
		</div>

		<p class="hide-if-no-js">
			<a class="upload-custom-img <?= $has_file ? 'hidden' : '' ?>"
			   href="<?= $upload_link ?>">Set file</a>
			<a class="delete-custom-img <?= $has_file ? '' : 'hidden' ?>"
			   href="#">Remove file</a>
		</p>

		<input class="custom-img-id" name="<?= $field_name ?>" type="hidden"
			   value="<?php echo esc_attr( $your_img_id ); ?>"/>

		<script>
			jQuery(function ($) {
				var frame,
						metaBox = $('#<?= esc_js( $meta_box_id ) ?>.postbox'),
						addImgLink = metaBox.find('.upload-custom-img'),
						delImgLink = metaBox.find('.delete-custom-img'),
						imgContainer = metaBox.find('.custom-img-container'),
						imgIdInput = metaBox.find('.custom-img-id');

				// ADD IMAGE LINK
				addImgLink.on('click', function (event) {
					event.preventDefault();

					if (frame) {
						frame.open();
						return;
					}

					// Create a new media frame
					frame = wp.media({
						multiple: false
					});

					frame.on('select', function () {
						var attachment = frame.state().get('selection').first().toJSON();
						imgContainer.append('<a href="' + attachment.url + '" alt="Download file" style="max-width:100%;">' + attachment.url + '</a>');
						imgIdInput.val(attachment.id);
						addImgLink.addClass('hidden');
						delImgLink.removeClass('hidden');
					});

					frame.open();
				});

				// DELETE IMAGE LINK
				delImgLink.on('click', function (event) {
					event.preventDefault();
					imgContainer.html('');
					addImgLink.removeClass('hidden');
					delImgLink.addClass('hidden');
					imgIdInput.val('');
				});
			});
		</script>
		<?php
	}
}
