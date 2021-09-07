<?php

namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\ACF\Util\Field_Val_Getter;

class Page_Sidebar extends A_Basic_Section implements I_Renderable {
	const FIELD_QUICK_LINKS_HEADER     = 'quick_links_header';
	const FIELD_QUICK_LINKS_ENTRIES    = 'quick_links_entries';
	const FIELD_QUICK_LINKS_ENTRY_LINK = 'quick_links_entry_link';

	/** @inheritdoc */
	protected static function _get_fields(): array {
		$quick_links_header     = static::gen_field_key( static::FIELD_QUICK_LINKS_HEADER );
		$quick_links_entries    = static::gen_field_key( static::FIELD_QUICK_LINKS_ENTRIES );
		$quick_links_entry_link = static::gen_field_key( static::FIELD_QUICK_LINKS_ENTRY_LINK );

		$return = array_merge(
			static::_gen_tab_field( 'Quick Links' ),
			array(
				static::FIELD_QUICK_LINKS_HEADER  => array(
					'key'   => $quick_links_header,
					'name'  => static::FIELD_QUICK_LINKS_HEADER,
					'label' => 'Header',
					'type'  => 'text',
				),
				static::FIELD_QUICK_LINKS_ENTRIES => array(
					'key'        => $quick_links_entries,
					'name'       => static::FIELD_QUICK_LINKS_ENTRIES,
					'label'      => 'Quick Links Entries',
					'type'       => 'repeater',
					'layout'     => 'block',
					'sub_fields' => array(
						static::FIELD_QUICK_LINKS_ENTRY_LINK => array(
							'key'           => $quick_links_entry_link,
							'name'          => static::FIELD_QUICK_LINKS_ENTRY_LINK,
							'label'         => 'Link',
							'type'          => 'link',
							'required'      => true,
							'return_format' => 'array',
						),
					),
				),
			),
		);

		return $return;
	}

	/** @inheritdoc */
	public static function prepare_register_args(): array {
		$args = array_merge(
			parent::prepare_register_args(),
			array(
				'title'    => 'Page Sidebar',
				/**
				 * Temporarily removing from Info Page in favor of Article Highlights sidebar logic
				 * This block should be removed altogether once Page Sidebar is no longer needed.
				 */
				// 'location' => array(
				// 	array(
				// 		array(
				// 			'param'    => 'page_template',
				// 			'operator' => '==',
				// 			'value'    => 'template-info-page.php',
				// 		),
				// 	),
				// ),
			)
		);

		return $args;
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$val = new Field_Val_Getter( static::class, $post, $data );

		$quick_links_header  = $val->get( static::FIELD_QUICK_LINKS_HEADER );
		$quick_links_entries = $val->get( static::FIELD_QUICK_LINKS_ENTRIES );

		//TODO: Frontend

		ob_start();
		?>
		<aside class="quick-links">
			<p class="quick-links__heading"><?php echo esc_html( $quick_links_header ); ?></p>
			<?php if ( ! empty( $quick_links_entries ) ) : ?>
				<ul class="quick-links__list">
				<?php foreach ( $quick_links_entries as $entry ) : ?>
					<li class="quick-links__item">
					<?php if ( ! empty( $entry[ static::FIELD_QUICK_LINKS_ENTRY_LINK ] ) ) : ?>
						<a class="quick-links__link" href="<?php echo esc_url( $entry[ static::FIELD_QUICK_LINKS_ENTRY_LINK ]['url'] ); ?>" target="<?php echo esc_attr( $entry[ static::FIELD_QUICK_LINKS_ENTRY_LINK ]['target'] ); ?>"><?php echo esc_attr( $entry[ static::FIELD_QUICK_LINKS_ENTRY_LINK ]['title'] ); ?></a>
					</li>
					<?php endif; ?>
				<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</aside>
		<?php
		return ob_get_clean();
	}
}
