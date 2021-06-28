<?php namespace TrevorWP\Theme\ACF\Field_Group;

class File_Download extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_TITLE                  = 'title';
	const FIELD_FILE_ENTRIES           = 'file_entries';
	const FIELD_FILE_ENTRY_TITLE       = 'file_entry_title';
	const FIELD_FILE_ENTRY_DESCRIPTION = 'file_entry_description';
	const FIELD_FILE_ENTRY_FILE        = 'file_entry_file';
	const FIELD_BUTTON                 = 'button';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$title                  = static::gen_field_key( static::FIELD_TITLE );
		$file_entries           = static::gen_field_key( static::FIELD_FILE_ENTRIES );
		$file_entry_title       = static::gen_field_key( static::FIELD_FILE_ENTRY_TITLE );
		$file_entry_description = static::gen_field_key( static::FIELD_FILE_ENTRY_DESCRIPTION );
		$file_entry_file        = static::gen_field_key( static::FIELD_FILE_ENTRY_FILE );
		$button                 = static::gen_field_key( static::FIELD_BUTTON );

		return array(
			'title'  => 'File Download',
			'fields' => array(
				static::FIELD_TITLE        => array(
					'key'      => $title,
					'name'     => static::FIELD_TITLE,
					'label'    => 'Title',
					'type'     => 'text',
					'required' => 1,
				),
				static::FIELD_FILE_ENTRIES => array(
					'key'          => $file_entries,
					'name'         => static::FIELD_FILE_ENTRIES,
					'label'        => 'File Entries',
					'type'         => 'repeater',
					'layout'       => 'block',
					'min'          => 1,
					'max'          => 3,
					'collapsed'    => $file_entry_title,
					'button_label' => 'Add File',
					'sub_fields'   => array(
						static::FIELD_FILE_ENTRY_TITLE => array(
							'key'      => $file_entry_title,
							'name'     => static::FIELD_FILE_ENTRY_TITLE,
							'label'    => 'Title',
							'type'     => 'text',
							'required' => 1,
						),
						static::FIELD_FILE_ENTRY_DESCRIPTION => array(
							'key'   => $file_entry_description,
							'name'  => static::FIELD_FILE_ENTRY_DESCRIPTION,
							'label' => 'Description',
							'type'  => 'textarea',
						),
						static::FIELD_FILE_ENTRY_FILE  => array(
							'key'           => $file_entry_file,
							'name'          => static::FIELD_FILE_ENTRY_FILE,
							'label'         => 'File',
							'type'          => 'file',
							'required'      => 1,
							'return_format' => 'array',
							'library'       => 'all',
						),
					),
				),
				static::FIELD_BUTTON       => array(
					'key'           => $button,
					'name'          => static::FIELD_BUTTON,
					'label'         => 'Button',
					'type'          => 'link',
					'return_format' => 'array',
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
				'title'      => 'File Download',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$title        = static::get_val( static::FIELD_TITLE );
		$file_entries = static::get_val( static::FIELD_FILE_ENTRIES );
		$button       = static::get_val( static::FIELD_BUTTON );

		ob_start();
		?>
		<div class="container mx-auto">
			<?php if ( ! empty( $title ) ) : ?>
				<h3><?php echo esc_html( $title ); ?></h3>
			<?php endif; ?>

			<?php if ( ! empty( $file_entries ) ) : ?>
				<div>
					<?php foreach ( $file_entries as $file ) : ?>
						<div>
							<?php if ( ! empty( $file[ static::FIELD_FILE_ENTRY_TITLE ] ) ) : ?>
								<h2><?php echo esc_html( $file[ static::FIELD_FILE_ENTRY_TITLE ] ); ?></h2>
							<?php endif; ?>

							<?php if ( ! empty( $file[ static::FIELD_FILE_ENTRY_DESCRIPTION ] ) ) : ?>
								<p><?php echo esc_html( $file[ static::FIELD_FILE_ENTRY_DESCRIPTION ] ); ?></p>
							<?php endif; ?>

							<?php if ( ! empty( $file[ static::FIELD_FILE_ENTRY_FILE ]['url'] ) ) : ?>
								<a href="<?php echo esc_url( $file[ static::FIELD_FILE_ENTRY_FILE ]['url'] ); ?>" download>Download</a>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $button['url'] ) ) : ?>
				<a href="<?php echo esc_url( $button['url'] ); ?>" target="<?php echo esc_attr( $button['target'] ); ?>"><?php echo esc_html( $button['title'] ); ?></a>
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
