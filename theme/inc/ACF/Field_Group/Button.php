<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\CPT;
use TrevorWP\Theme\ACF\Util\Field_Val_Getter;

class Button extends A_Field_Group implements I_Renderable {
	const FIELD_TYPE = 'type';
	const FIELD_LABEL = 'label';
	const FIELD_ACTION = 'action';
	const FIELD_LINK = 'link';
	const FIELD_PAGE_LINK = 'page_link';
	const FIELD_BUTTON_ATTR = 'button_attr';
	const FIELD_LABEL_ATTR = 'label_attr';

	/** @inheritDoc */
	public static function prepare_register_args(): array {
		$type        = static::gen_field_key( static::FIELD_TYPE );
		$label       = static::gen_field_key( static::FIELD_LABEL );
		$action      = static::gen_field_key( static::FIELD_ACTION );
		$link        = static::gen_field_key( static::FIELD_LINK );
		$page_link   = static::gen_field_key( static::FIELD_PAGE_LINK );
		$button_attr = static::gen_field_key( static::FIELD_BUTTON_ATTR );
		$label_attr  = static::gen_field_key( static::FIELD_LABEL_ATTR );

		return [
				'title'  => 'Page Button',
				'fields' => array_merge(
						static::_gen_tab_field( 'General' ),
						[
								static::FIELD_TYPE      => [
										'key'           => $type,
										'name'          => static::FIELD_TYPE,
										'label'         => 'Type',
										'type'          => 'select',
										'required'      => true,
										'choices'       => [
												'primary'   => 'Primary',
												'secondary' => 'Secondary',
										],
										'default_value' => 'primary',
										'return_format' => 'value',
								],
								static::FIELD_LABEL     => [
										'key'      => $label,
										'name'     => static::FIELD_LABEL,
										'label'    => 'Label',
										'type'     => 'text',
										'required' => true,
								],
								static::FIELD_ACTION    => [
										'key'           => $action,
										'name'          => static::FIELD_ACTION,
										'label'         => 'Action',
										'type'          => 'select',
										'required'      => true,
										'choices'       => [
												'link'      => 'Link',
												'page_link' => 'Page Link',
										],
										'default_value' => 'page_link',
										'allow_null'    => true,
										'multiple'      => false,
								],
								static::FIELD_LINK      => [
										'key'               => $link,
										'label'             => 'Link',
										'name'              => static::FIELD_LINK,
										'type'              => 'link',
										'conditional_logic' => [
												[
														[
																'field'    => $action,
																'operator' => '==',
																'value'    => 'link',
														],
												],
										],
										'return_format'     => 'array',
								],
								static::FIELD_PAGE_LINK => [
										'key'               => $page_link,
										'name'              => static::FIELD_PAGE_LINK,
										'label'             => 'Page Link',
										'type'              => 'page_link',
										'required'          => true,
										'conditional_logic' => [
												[
														[
																'field'    => $action,
																'operator' => '==',
																'value'    => 'page_link',
														],
												],
										],
										'allow_null'        => false,
										'multiple'          => false,
										'return_format'     => 'object',
										'ui'                => true,
								]
						],
						static::_gen_tab_field( 'Attributes' ),
						[
								static::FIELD_BUTTON_ATTR => DOM_Attr::clone( [
										'key'     => $button_attr,
										'name'    => static::FIELD_BUTTON_ATTR,
										'label'   => 'Button',
										'display' => 'group',
								] ),
								static::FIELD_LABEL_ATTR  => DOM_Attr::clone( [
										'key'     => $label_attr,
										'name'    => static::FIELD_LABEL_ATTR,
										'label'   => 'Label',
										'display' => 'group',
								] ),
						],
				),
		];
	}

	/** @inheritdoc */
	public static function render( $post = false, array $data = null, array $options = [] ): ?string {
		$val       = new Field_Val_Getter( static::class, $post, $data );
		$btn_cls   = array_merge( [ 'page-btn' ], ( $options['btn_cls'] ?? [] ) );
		$label_cls = array_merge( [ 'page-btn-label' ], ( $options['label_cls'] ?? [] ) );

		$type      = $val->get( static::FIELD_TYPE );
		$btn_cls[] = "page-btn-{$type}";
		$btn_attr  = $val->get( static::FIELD_BUTTON_ATTR );

		$id = uniqid( 'quiz-', true );

		if ( empty( $btn_attr[ DOM_Attr::FIELD_ATTRIBUTES ] ) ) {
			$btn_attr[ DOM_Attr::FIELD_ATTRIBUTES ] = [];
		}

		# Links
		if ( $val->get( static::FIELD_ACTION ) == 'link' ) {
			if ( ( $link = $val->get( static::FIELD_LINK ) ) && is_array( $link ) ) {
				foreach (
						array_filter( [
								'title'  => $link['title'] ?? null,
								'href'   => $link['url'] ?? null,
								'target' => $link['target'] ?? null,
						] ) as $k => $v
				) {
					$btn_attr[ DOM_Attr::FIELD_ATTRIBUTES ][] = [
							DOM_Attr::FIELD_ATTR_KEY => $k,
							DOM_Attr::FIELD_ATTR_VAL => $v,
					];
				}
			}
		} else if ( $val->get( static::FIELD_ACTION ) == 'page_link' ) {
			if ( $page_link = $val->get( static::FIELD_PAGE_LINK ) ) {
				$btn_attr[ DOM_Attr::FIELD_ATTRIBUTES ][] = [
						DOM_Attr::FIELD_ATTR_KEY => 'href',
						DOM_Attr::FIELD_ATTR_VAL => $page_link,
				];
			}
		}

		ob_start(); ?>
		<a <?= DOM_Attr::render_attrs_of( $btn_attr, $btn_cls ) ?>>
			<span <?= DOM_Attr::render_attrs_of( $val->get( static::FIELD_LABEL_ATTR ), $label_cls ) ?>>
				<?= $val->get( static::FIELD_LABEL ) ?>
			</span>
		</a>
		<?php return ob_get_clean();
	}
}
