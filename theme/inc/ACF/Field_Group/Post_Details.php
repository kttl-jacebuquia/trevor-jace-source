<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\CPT;
use TrevorWP\Theme\ACF\Util\Field_Val_Getter;

class Post_Details extends A_Field_Group {
	const FIELD_FILE_ENTRIES     = 'post_details_file_entries';
	const FIELD_FILE_ENTRY_LABEL = 'post_details_file_entry_label';
	const FIELD_FILE_ENTRY_FILE  = 'post_details_file_entry_file';

	/** @inheritDoc */
	public static function prepare_register_args(): array {
		$file_entries     = static::gen_field_key( static::FIELD_FILE_ENTRIES );
		$file_entry_label = static::gen_field_key( static::FIELD_FILE_ENTRY_LABEL );
		$file_entry_file  = static::gen_field_key( static::FIELD_FILE_ENTRY_FILE );

		$title = self::get_post_type_title();

		return array(
			'title'    => "{$title} Details",
			'fields'   => array(
				static::FIELD_FILE_ENTRIES => array(
					'key'        => $file_entries,
					'name'       => static::FIELD_FILE_ENTRIES,
					'label'      => 'PDF Entries',
					'type'       => 'repeater',
					'layout'     => 'block',
					'collapsed'  => $file_entry_label,
					'sub_fields' => array(
						static::FIELD_FILE_ENTRY_LABEL => array(
							'key'       => $file_entry_label,
							'name'      => static::FIELD_FILE_ENTRY_LABEL,
							'label'     => 'Label',
							'type'      => 'text',
							'required'  => 1,
							'maxlength' => 19,
						),
						static::FIELD_FILE_ENTRY_FILE  => array(
							'key'           => $file_entry_file,
							'label'         => 'PDF',
							'name'          => static::FIELD_FILE_ENTRY_FILE,
							'type'          => 'file',
							'required'      => 1,
							'mime_types'    => 'PDF',
							'return_format' => 'url',
						),
					),
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => CPT\Post::POST_TYPE,
					),
				),
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => CPT\RC\Guide::POST_TYPE,
					),
				),
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => CPT\RC\Article::POST_TYPE,
					),
				),
			),
		);
	}

	/**
	 * Get title based on the current post type
	 */
	public static function get_post_type_title() {
		$post_type = ! empty( $_GET['post_type'] ) ? $_GET['post_type'] : '';
		$post_type = ! empty( $_GET['post'] ) ? get_post_type( $_GET['post'] ) : $post_type;

		$title = 'Post';

		switch ( $post_type ) {
			case CPT\Post::POST_TYPE:
				$title = 'Post';
				break;
			case CPT\RC\Guide::POST_TYPE:
				$title = 'Guide';
				break;
			case CPT\RC\Article::POST_TYPE:
				$title = 'Article';
				break;
		}

		return $title;
	}

	/**
	 * @param \WP_Post $post
	 *
	 * @return string
	 */
	public static function render_multiple_files( \WP_Post $post ): string {
		$val          = new Field_Val_Getter( self::class, $post );
		$file_entries = $val->get( static::FIELD_FILE_ENTRIES );
		$bg_color     = 'bg-indigo';

		if ( empty( $file_entries ) ) {
			return '';
		}

		if ( CPT\Post::POST_TYPE === $post->post_type ) {
			$bg_color = 'bg-teal-dark';
		}

		ob_start();
		?>
		<div class="post-file-wrap">
			<?php foreach ( $file_entries as $file ) : ?>
				<?php if ( ! empty( $file[ static::FIELD_FILE_ENTRY_FILE ] ) && ! empty( $file[ static::FIELD_FILE_ENTRY_LABEL ] ) ) : ?>
					<a class="btn post-file-btn <?php echo esc_attr( $bg_color ); ?>" href="<?php echo esc_url( $file[ static::FIELD_FILE_ENTRY_FILE ] ); ?>">
						<span class="post-file-btn-cta">
							<?php echo esc_html( $file[ static::FIELD_FILE_ENTRY_LABEL ] ); ?>
						</span><i class="trevor-ti-download post-file-btn-icn"></i>
					</a>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
		<?php
		return ob_get_clean();
	}
}
