<?php namespace TrevorWP\Theme\ACF\Field_Group;

class Video_Player extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_LESSON                    = 'lesson';
	const FIELD_VIDEO_ENTRIES             = 'video_entries';
	const FIELD_VIDEO_ENTRY_HEADER        = 'video_entry_header';
	const FIELD_VIDEO_ENTRY_DESCRIPTION   = 'video_entry_description';
	const FIELD_VIDEO_ENTRY_FILE_LABEL    = 'video_entry_file_label';
	const FIELD_VIDEO_ENTRY_FILE_DOWNLOAD = 'video_entry_file_download';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$lesson                    = static::gen_field_key( static::FIELD_LESSON );
		$video_entries             = static::gen_field_key( static::FIELD_VIDEO_ENTRIES );
		$video_entry_header        = static::gen_field_key( static::FIELD_VIDEO_ENTRY_HEADER );
		$video_entry_description   = static::gen_field_key( static::FIELD_VIDEO_ENTRY_DESCRIPTION );
		$video_entry_file_label    = static::gen_field_key( static::FIELD_VIDEO_ENTRY_FILE_LABEL );
		$video_entry_file_download = static::gen_field_key( static::FIELD_VIDEO_ENTRY_FILE_DOWNLOAD );

		return array(
			'title'  => 'Video Player',
			'fields' => array(
				static::FIELD_LESSON        => array(
					'key'           => $lesson,
					'name'          => static::FIELD_LESSON,
					'label'         => 'Lesson',
					'type'          => 'text',
					'required'      => 1,
					'default_value' => 'Lessons',
				),
				static::FIELD_VIDEO_ENTRIES => array(
					'key'        => $video_entries,
					'name'       => static::FIELD_VIDEO_ENTRIES,
					'label'      => 'Video Entries',
					'type'       => 'repeater',
					'layout'     => 'block',
					'min'        => 1,
					'max'        => 6,
					'collapsed'  => $video_entry_header,
					'sub_fields' => array(
						static::FIELD_VIDEO_ENTRY_HEADER => array(
							'key'      => $video_entry_header,
							'name'     => static::FIELD_VIDEO_ENTRY_HEADER,
							'label'    => 'Header',
							'type'     => 'text',
							'required' => 1,
						),
						static::FIELD_VIDEO_ENTRY_DESCRIPTION => array(
							'key'   => $video_entry_description,
							'name'  => static::FIELD_VIDEO_ENTRY_DESCRIPTION,
							'label' => 'Body',
							'type'  => 'textarea',
						),
						static::FIELD_VIDEO_ENTRY_FILE_LABEL => array(
							'key'     => $video_entry_file_label,
							'name'    => static::FIELD_VIDEO_ENTRY_FILE_LABEL,
							'label'   => 'File Label',
							'type'    => 'text',
							'wrapper' => array(
								'width' => '50%',
							),
						),
						static::FIELD_VIDEO_ENTRY_FILE_DOWNLOAD => array(
							'key'     => $video_entry_file_download,
							'name'    => static::FIELD_VIDEO_ENTRY_FILE_DOWNLOAD,
							'label'   => 'File Download',
							'type'    => 'file',
							'wrapper' => array(
								'width' => '50%',
							),
						),
					),
				),
			),
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function get_block_args(): array {
		return array_merge(
			parent::get_block_args(),
			array(
				'name'       => static::get_key(),
				'title'      => 'Video Player',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$lesson        = static::get_val( static::FIELD_LESSON );
		$video_entries = static::get_val( static::FIELD_VIDEO_ENTRIES );

		$lesson = count( $video_entries ) . ' ' . $lesson;

		ob_start();
		// Next Step: FE
		?>
		<div>
			<p><?php echo esc_html( $lesson ); ?></p>
			<?php if ( ! empty( $video_entries ) ) : ?>
				<?php foreach ( $video_entries as $video ) : ?>
					<?php if ( ! empty( $video[ static::FIELD_VIDEO_ENTRY_HEADER ] ) ) : ?>
						<h1><?php echo esc_html( $video[ static::FIELD_VIDEO_ENTRY_HEADER ] ); ?></h1>
					<?php endif; ?>

					<?php if ( ! empty( $video[ static::FIELD_VIDEO_ENTRY_DESCRIPTION ] ) ) : ?>
						<p><?php echo esc_html( $video[ static::FIELD_VIDEO_ENTRY_DESCRIPTION ] ); ?></p>
					<?php endif; ?>

					<?php if ( ! empty( $video[ static::FIELD_VIDEO_ENTRY_FILE_DOWNLOAD ]['url'] ) ) : ?>
						<a href="<?php echo esc_url( $video[ static::FIELD_VIDEO_ENTRY_FILE_DOWNLOAD ]['url'] ); ?>" download><?php echo esc_html( $video[ static::FIELD_VIDEO_ENTRY_FILE_LABEL ] ); ?></a>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>
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
