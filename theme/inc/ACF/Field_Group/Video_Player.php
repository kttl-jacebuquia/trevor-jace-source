<?php namespace TrevorWP\Theme\ACF\Field_Group;

class Video_Player extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_LESSON                    = 'lesson';
	const FIELD_VIDEO_ENTRIES             = 'video_entries';
	const FIELD_VIDEO_ENTRY_THUMBNAIL     = 'video_entry_thumbnail';
	const FIELD_VIDEO_ENTRY_VIMEO         = 'video_entry_vimeo';
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
		$video_entry_thumbnail     = static::gen_field_key( static::FIELD_VIDEO_ENTRY_THUMBNAIL );
		$video_entry_vimeo         = static::gen_field_key( static::FIELD_VIDEO_ENTRY_VIMEO );
		$video_entry_header        = static::gen_field_key( static::FIELD_VIDEO_ENTRY_HEADER );
		$video_entry_description   = static::gen_field_key( static::FIELD_VIDEO_ENTRY_DESCRIPTION );
		$video_entry_file_label    = static::gen_field_key( static::FIELD_VIDEO_ENTRY_FILE_LABEL );
		$video_entry_file_download = static::gen_field_key( static::FIELD_VIDEO_ENTRY_FILE_DOWNLOAD );

		return array(
			'title'  => 'Lessons Video Player',
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
						static::FIELD_VIDEO_ENTRY_THUMBNAIL => array(
							'key'          => $video_entry_thumbnail,
							'name'         => static::FIELD_VIDEO_ENTRY_THUMBNAIL,
							'label'        => 'Thumbnail',
							'type'         => 'image',
							'preview_size' => 'thumbnail',
						),
						static::FIELD_VIDEO_ENTRY_VIMEO  => array(
							'key'      => $video_entry_vimeo,
							'name'     => static::FIELD_VIDEO_ENTRY_VIMEO,
							'label'    => 'Vimeo or Youtube URL',
							'type'     => 'oembed',
							'required' => 1,
						),
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
				'title'      => 'Lessons Video Player',
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

		if ( ! empty( $video_entries ) ) {
			$lesson = count( $video_entries ) . ' ' . $lesson;
		}

		// Extract embed info from embed html
		foreach ( $video_entries as &$video_entry ) {
			$embed_html = $video_entry[ static::FIELD_VIDEO_ENTRY_VIMEO ];
			preg_match_all( '/(src|width|height)="([^"]+)"/i', $embed_html, $matches );

			foreach ( $matches[1] as $index => $matched_key ) {
				$video_entry[ $matched_key ] = $matches[2][ $index ];
			}

			// Thumbnail
			if ( ! empty( $video_entry[ static::FIELD_VIDEO_ENTRY_THUMBNAIL ] ) ) {
				$video_entry['thumbnail_id'] = $video_entry[ static::FIELD_VIDEO_ENTRY_THUMBNAIL ]['ID'];
				$video_entry['thumbnail']    = $video_entry[ static::FIELD_VIDEO_ENTRY_THUMBNAIL ]['sizes']['thumbnail'];
				$video_entry['poster']       = $video_entry[ static::FIELD_VIDEO_ENTRY_THUMBNAIL ]['url'];
			}

			// File Download
			if ( ! empty( $video_entry[ static::FIELD_VIDEO_ENTRY_FILE_DOWNLOAD ]['url'] ) ) {
				$video_entry['download_url']   = $video_entry[ static::FIELD_VIDEO_ENTRY_FILE_DOWNLOAD ]['url'];
				$video_entry['download_label'] = $video_entry[ static::FIELD_VIDEO_ENTRY_FILE_LABEL ];
			}
		}

		$first_video = $video_entries[0];

		ob_start();
		// Next Step: FE
		?>
		<?php if ( ! empty( $video_entries ) ) : ?>
			<div class="lessons-video-player">
				<div class="lessons-video-player__container">

					<!-- Player -->
					<div class="lessons-video-player__lesson">
						<div class="lessons-video-player__player" data-lesson-id="">
							<!-- IFrame -->
							<div class="lessons-video-player__vimeo-placeholder"></div>
							<div class="lessons-video-player__youtube-placeholder">
								<div class="lessons-video-player__youtube-iframe-replacement"></div>
							</div>
							<!-- Poster -->
							<figure class="lessons-video-player__player-poster" aria-hidden="true">
								<img src="<?php echo $first_video['thumbnail_id']; ?>" />
							</figure>
							<!-- Play button -->
							<button class="lessons-video-player__play trevor-ti-play"></button>
						</div>
						<div class="lessons-video-player__title" data-number="1"><?php echo esc_html( $first_video[ static::FIELD_VIDEO_ENTRY_HEADER ] ); ?></div>
						<div class="lessons-video-player__body"><?php echo esc_html( $first_video[ static::FIELD_VIDEO_ENTRY_HEADER ] ); ?></div>
						<div class="lessons-video-player__download">
							<a href="test.pdf" class="lessons-video-player__download-link wave-underline" download>Download Lesson Plan 1</a>
						</div>
					</div>

					<!-- List -->
					<div class="lessons-video-player__playlist">
						<h3 class="lessons-video-player__playlist-title"><?php echo esc_html( $lesson ); ?></h3>
						<div class="lessons-video-player__playlist-items" role="list">
							<?php $counter = 1; ?>
							<?php foreach ( $video_entries as $video ) : ?>
								<?php if ( ! empty( $video[ static::FIELD_VIDEO_ENTRY_VIMEO ] ) ) : ?>
									<button
										<?php
											echo self::render_attrs(
												array( 'lessons-video-player__playlist-item' ),
												array(
													'data-number' => esc_attr( $counter++ ),
													'data-src' => esc_attr( $video['src'] ),
													'data-thumbnail-id' => esc_attr( $video['thumbnail_id'] ),
													'data-thumbnail' => esc_attr( $video['thumbnail'] ),
													'data-poster' => esc_attr( $video['poster'] ),
													'data-title' => esc_attr( $video[ static::FIELD_VIDEO_ENTRY_HEADER ] ),
													'data-description' => esc_attr( $video[ static::FIELD_VIDEO_ENTRY_DESCRIPTION ] ),
													'data-download-url' => esc_attr( $video['download_url'] ),
													'data-download-label' => esc_attr( $video['download_label'] ),
													'data-lesson-id' => esc_attr( uniqid( 'lesson_' ) ),
													'aria-label' => esc_attr( $video[ static::FIELD_VIDEO_ENTRY_HEADER ] . ', click to select this video' ),
													'role' => 'listitem',
												),
											);
										?>
									>
										<figure class="lessons-video-player__playlist-item-thumbnail">
											<?php if ( ! empty( $video['thumbnail_id'] ) ) : ?>
												<?php
													echo wp_get_attachment_image(
														$video['thumbnail_id'],
														'thumbnail',
														false,
														array(
															'class' => 'lessons-video-player__playlist-thumbnail',
														),
													);
												?>
											<?php else : ?>
												<img class="lessons-video-player__playlist-thumbnail">
											<?php endif; ?>
											<span class="trevor-ti-play lessons-video-player__playlist-thumbnail-icon"></span>
										</figure>
										<div class="lessons-video-player__playlist-item-content">
											<?php if ( ! empty( $video[ static::FIELD_VIDEO_ENTRY_HEADER ] ) ) : ?>
												<h4 class="lessons-video-player__playlist-item-title"><?php echo esc_html( $video[ static::FIELD_VIDEO_ENTRY_HEADER ] ); ?></h4>
											<?php endif; ?>
											<aside class="lessons-video-player__playlist-item-duration">8:08</aside>
										</div>
										<div class="lessons-video-player__playlist-icon trevor-ti-checkmark"></div>
									</button>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
					</div>

				</div>
			</div>
		<?php endif; ?>
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
