<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Util\Tools;

class URL_File_List extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_TITLE               = 'title';
	const FIELD_DESCRIPTION         = 'description';
	const FIELD_URL_FILE_ENTRIES    = 'url_file_entries';
	const FIELD_URL_FILE_ENTRY_LINK = 'url_file_entry_link';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$title               = static::gen_field_key( static::FIELD_TITLE );
		$description         = static::gen_field_key( static::FIELD_DESCRIPTION );
		$url_file_entries    = static::gen_field_key( static::FIELD_URL_FILE_ENTRIES );
		$url_file_entry_link = static::gen_field_key( static::FIELD_URL_FILE_ENTRY_LINK );

		return array(
			'title'  => 'Links Module',
			'fields' => array(
				static::FIELD_TITLE            => array(
					'key'   => $title,
					'name'  => static::FIELD_TITLE,
					'label' => 'Title',
					'type'  => 'text',
				),
				static::FIELD_DESCRIPTION      => array(
					'key'   => $description,
					'name'  => static::FIELD_DESCRIPTION,
					'label' => 'Description',
					'type'  => 'textarea',
				),
				static::FIELD_URL_FILE_ENTRIES => array(
					'key'        => $url_file_entries,
					'name'       => static::FIELD_URL_FILE_ENTRIES,
					'label'      => 'Links',
					'type'       => 'repeater',
					'layout'     => 'block',
					'sub_fields' => array(
						static::FIELD_URL_FILE_ENTRY_LINK => array(
							'key'           => $url_file_entry_link,
							'name'          => static::FIELD_URL_FILE_ENTRY_LINK,
							'label'         => 'Link',
							'type'          => 'link',
							'return_format' => 'array',
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
				'title'      => 'Links Module',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$title            = static::get_val( static::FIELD_TITLE );
		$description      = static::get_val( static::FIELD_DESCRIPTION );
		$url_file_entries = static::get_val( static::FIELD_URL_FILE_ENTRIES );

		$attrs = array(
			'class' => 'links',
		);

		ob_start();
		?>
		<div <?php echo Tools::flat_attr( $attrs ); ?> >
			<div class="links__container">
				<div class="links__content">
					<?php if ( ! empty( $title ) ) : ?>
						<h2 class="links__heading"><?php echo $title; ?></h2>
					<?php endif; ?>
					<?php if ( ! empty( $description ) ) : ?>
						<p class="links__description"><?php echo $description; ?></p>
					<?php endif; ?>
					<?php if ( ! empty( $url_file_entries ) ) : ?>
						<ul class="links__items">
							<?php foreach ( $url_file_entries as $entry ) : ?>
								<li class="links__item"><a href="<?php echo esc_url( $entry['url_file_entry_link']['url'] ); ?>" target="<?php echo esc_attr( $entry['url_file_entry_link']['target'] ); ?>"><?php echo esc_attr( $entry['url_file_entry_link']['title'] ); ?></a></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
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
