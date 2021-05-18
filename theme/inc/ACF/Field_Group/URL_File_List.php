<?php namespace TrevorWP\Theme\ACF\Field_Group;

class URL_File_List extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_URL_FILE_ENTRIES          = 'url_file_entries';
	const FIELD_URL_FILE_ENTRY_TYPE       = 'url_file_entry_type';
	const FIELD_URL_FILE_ENTRY_LINK       = 'url_file_entry_link';
	const FIELD_URL_FILE_ENTRY_FILE_GROUP = 'url_file_entry_group';
	const FIELD_URL_FILE_ENTRY_FILE_LABEL = 'url_file_entry_label';
	const FIELD_URL_FILE_ENTRY_FILE       = 'url_file_entry_file';
	const FIELD_ATTR                      = 'attr';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$url_file_entries          = static::gen_field_key( static::FIELD_URL_FILE_ENTRIES );
		$url_file_entry_type       = static::gen_field_key( static::FIELD_URL_FILE_ENTRY_TYPE );
		$url_file_entry_link       = static::gen_field_key( static::FIELD_URL_FILE_ENTRY_LINK );
		$url_file_entry_file_group = static::gen_field_key( static::FIELD_URL_FILE_ENTRY_FILE_GROUP );
		$url_file_entry_file_label = static::gen_field_key( static::FIELD_URL_FILE_ENTRY_FILE_LABEL );
		$url_file_entry_file       = static::gen_field_key( static::FIELD_URL_FILE_ENTRY_FILE );
		$attr                      = static::gen_field_key( static::FIELD_ATTR );

		return array(
			'title'  => 'URL|File List',
			'fields' => array(
				static::FIELD_URL_FILE_ENTRIES => array(
					'key'        => $url_file_entries,
					'name'       => static::FIELD_URL_FILE_ENTRIES,
					'label'      => 'URL|File Entries',
					'type'       => 'repeater',
					'required'   => true,
					'layout'     => 'block',
					'collapsed'  => $url_file_entry_type,
					'sub_fields' => array(
						static::FIELD_URL_FILE_ENTRY_TYPE => array(
							'key'     => $url_file_entry_type,
							'name'    => static::FIELD_URL_FILE_ENTRY_TYPE,
							'label'   => 'Type',
							'type'    => 'select',
							'choices' => array(
								'link' => 'Link',
								'file' => 'File',
							),
						),
						static::FIELD_URL_FILE_ENTRY_LINK => array(
							'key'               => $url_file_entry_link,
							'name'              => static::FIELD_URL_FILE_ENTRY_LINK,
							'label'             => 'Link',
							'type'              => 'link',
							'return_format'     => 'array',
							'conditional_logic' => array(
								array(
									array(
										'field'    => $url_file_entry_type,
										'operator' => '==',
										'value'    => 'link',
									),
								),
							),
						),
						static::FIELD_URL_FILE_ENTRY_FILE_GROUP => array(
							'key'               => $url_file_entry_file_group,
							'name'              => static::FIELD_URL_FILE_ENTRY_FILE_GROUP,
							'label'             => 'File',
							'type'              => 'group',
							'conditional_logic' => array(
								array(
									array(
										'field'    => $url_file_entry_type,
										'operator' => '==',
										'value'    => 'file',
									),
								),
							),
							'layout'            => 'block',
							'sub_fields'        => array(
								static::FIELD_URL_FILE_ENTRY_FILE_LABEL => array(
									'key'     => $url_file_entry_file_label,
									'name'    => static::FIELD_URL_FILE_ENTRY_FILE_LABEL,
									'label'   => 'Label',
									'type'    => 'text',
									'wrapper' => array(
										'width' => '50%',
									),
								),
								static::FIELD_URL_FILE_ENTRY_FILE => array(
									'key'           => $url_file_entry_file,
									'name'          => static::FIELD_URL_FILE_ENTRY_FILE,
									'label'         => 'File',
									'type'          => 'file',
									'return_format' => 'array',
									'library'       => 'all',
									'wrapper'       => array(
										'width' => '50%',
									),
								),
							),
						),
					),
				),
				static::FIELD_ATTR             => DOM_Attr::clone(
					array(
						'key'   => $attr,
						'name'  => static::FIELD_ATTR,
						'label' => '',
					)
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
				'title'      => 'URL|File List',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$url_file_entries = static::get_val( static::FIELD_URL_FILE_ENTRIES );

		ob_start();
		?>
		<div <?php echo DOM_Attr::render_attrs_of( static::get_val( static::FIELD_ATTR ) ); ?> >
			<div class="links-content">
			<?php if ( ! empty( $url_file_entries ) ) : ?>
				<ul>
					<?php foreach ( $url_file_entries as $entry ) : ?>
						<?php if ( 'link' === $entry['url_file_entry_type'] ) : ?>
							<li><a href="<?php echo esc_url( $entry['url_file_entry_link']['url'] ); ?>" target="<?php echo esc_attr( $entry['url_file_entry_link']['target'] ); ?>"><?php echo esc_attr( $entry['url_file_entry_link']['title'] ); ?></a></li>
						<?php elseif ( 'file' === $entry['url_file_entry_type'] ) : ?>
							<li><a href="<?php echo esc_url( $entry['url_file_entry_group']['url_file_entry_file']['url'] ); ?>" download><?php echo esc_attr( $entry['url_file_entry_group']['url_file_entry_label'] ); ?></a></li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
			</div>
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
