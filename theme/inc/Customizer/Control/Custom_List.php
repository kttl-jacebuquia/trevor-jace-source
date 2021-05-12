<?php namespace TrevorWP\Theme\Customizer\Control;

include_once ABSPATH . 'wp-includes/class-wp-customize-control.php';

/**
 * Custom List Controller
 */
class Custom_List extends \WP_Customize_Control {
	use Mixin\React_Controller;

	/* Fields */
	const FIELD_TYPE_INPUT    = 'input';
	const FIELD_TYPE_TEXTAREA = 'textarea';
	const FIELD_TYPE_MEDIA    = 'media';

	/** @inheritDoc */
	public $type = 'custom_list';

	/* Field Sets */
	const FIELDSET_CAROUSEL = array(
		'img'     => array(
			'type'      => self::FIELD_TYPE_MEDIA,
			'label'     => 'Media',
			'mime_type' => 'image',
		),
		'caption' => array(
			'type'  => self::FIELD_TYPE_TEXTAREA,
			'label' => 'Description',
		),
		'cta_txt' => array(
			'type'  => self::FIELD_TYPE_INPUT,
			'label' => 'CTA Text',
		),
		'cta_url' => array(
			'type'  => self::FIELD_TYPE_INPUT,
			'label' => 'CTA Url',
		),
	);

	const FIELDSET_QUOTE = array(
		'quote' => array(
			'type'  => self::FIELD_TYPE_TEXTAREA,
			'label' => 'Quote',
		),
		'cite'  => array(
			'type'       => self::FIELD_TYPE_INPUT,
			'input_type' => 'text',
			'label'      => 'Cite',
		),
		'img'   => array(
			'type'      => self::FIELD_TYPE_MEDIA,
			'label'     => 'Image',
			'mime_type' => 'image',
		),
	);

	/**
	 * @var array[]
	 */
	public $fields = array();

	/** @inheritDoc */
	public function json() {
		$etx = array(
			'fields'        => $this->fields,
			'default_value' => $this->get_default_value(),
		);

		$parent = parent::json();

		return array_merge( $parent, $etx );
	}

	/**
	 * @return array
	 */
	public function get_default_value(): array {
		$values = $this->value();

		if ( empty( $values ) || ! is_array( $values ) ) {
			$values = array();
		}

		return $values;
	}
}
