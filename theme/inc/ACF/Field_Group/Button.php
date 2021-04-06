<?php namespace TrevorWP\Theme\ACF\Field_Group;

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
				'fields' => [
						static::FIELD_TYPE        => [
								'key'           => $type,
								'name'          => static::FIELD_TYPE,
								'label'         => 'Type',
								'type'          => 'select',
								'required'      => 1,
								'choices'       => [
										'primary'   => 'Primary',
										'secondary' => 'Secondary',
								],
								'default_value' => 'primary',
								'return_format' => 'value',
						],
						static::FIELD_LABEL       => [
								'key'      => $label,
								'name'     => static::FIELD_LABEL,
								'label'    => 'Label',
								'type'     => 'text',
								'required' => 1,
						],
						static::FIELD_ACTION      => [
								'key'           => $action,
								'name'          => static::FIELD_ACTION,
								'label'         => 'Action',
								'type'          => 'select',
								'required'      => 1,
								'choices'       => [
										'link'      => 'Link',
										'page_link' => 'Page Link',
								],
								'default_value' => 'page_link',
								'allow_null'    => 1,
								'multiple'      => 0,
						],
						static::FIELD_LINK        => [
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
						static::FIELD_PAGE_LINK   => [
								'key'               => $page_link,
								'name'              => static::FIELD_PAGE_LINK,
								'label'             => 'Page Link',
								'type'              => 'post_object',
								'required'          => 1,
								'conditional_logic' => [
										[
												[
														'field'    => $action,
														'operator' => '==',
														'value'    => 'page_link',
												],
										],
								],
								'allow_null'        => 0,
								'multiple'          => 0,
								'return_format'     => 'object',
								'ui'                => 1,
						],
						static::FIELD_BUTTON_ATTR => DOM_Attr::clone( [
								'key'   => $button_attr,
								'name'  => static::FIELD_BUTTON_ATTR,
								'label' => 'Button Attributes',
						] ),
						static::FIELD_LABEL_ATTR  => DOM_Attr::clone( [
								'key'   => $label_attr,
								'name'  => static::FIELD_LABEL_ATTR,
								'label' => 'Label Attributes',
						] ),
				],
		];
	}

	/** @inheritdoc */
	public static function render( $post = false, array $data = null, array $options = [] ): ?string {
		$val = new Field_Val_Getter( static::class, $post, $data );

		$btn_cls   = [ 'page-btn' ];
		$label_cls = [ 'page-btn-label' ];

		$xx = $val->get( static::FIELD_BUTTON_ATTR );

		ob_start(); ?>
		<button <?= DOM_Attr::render_attrs_of( $val->get( static::FIELD_BUTTON_ATTR ), $btn_cls ) ?>>
			<span <?= DOM_Attr::render_attrs_of( $val->get( static::FIELD_LABEL_ATTR ), $label_cls ) ?>>
				<?= $val->get( static::FIELD_LABEL ) ?>
			</span>
		</button>
		<?php return ob_get_clean();
	}
}
