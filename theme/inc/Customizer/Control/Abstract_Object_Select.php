<?php namespace TrevorWP\Theme\Customizer\Control;

use WP_Customize_Control;

/**
 * Abstract Object Selector
 */
abstract class Abstract_Object_Select extends WP_Customize_Control {
	use Mixin\React_Controller;

	/** @inheritDoc */
	public $type = 'object_selector';

	/**
	 * @var bool Whether allow to ordering.
	 */
	public $allow_order = true;

	/**
	 * @var bool
	 */
	public $allow_multiple = true;

	/** @inheritDoc */
	public function json() {
		$etx = array(
			'object_type'    => $this->get_object_type(),
			'default_value'  => $this->get_default_value(),
			'allow_multiple' => $this->allow_multiple,
			'allow_order'    => $this->allow_order,
		);

		if ( isset( $this->meta_key ) ) {
			$ext['meta_key'] = $this->meta_key;
		}

		return array_merge( parent::json(), $etx );
	}

	/**
	 * @return string e.g. post,taxonomy
	 */
	abstract public function get_object_type(): string;

	/**
	 * @return array
	 */
	abstract public function get_object_query_args(): array;

	/**
	 * @return mixed
	 */
	abstract public function get_default_value();
}
