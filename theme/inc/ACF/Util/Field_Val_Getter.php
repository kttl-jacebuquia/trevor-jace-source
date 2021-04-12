<?php namespace TrevorWP\Theme\ACF\Util;


use TrevorWP\Theme\ACF\Field_Group\A_Field_Group;

class Field_Val_Getter {
	/**
	 * @var A_Field_Group
	 */
	public $class;

	/**
	 * @var mixed|null
	 */
	public $data;

	/**
	 * @var null|\WP_Post|int
	 */
	public $post;

	public function __construct( $class, $post, &$data = null ) {
		$this->class = $class;
		$this->data  = $data;
		$this->post  = $post;
	}

	/**
	 * @param $name
	 *
	 * @return mixed|null
	 */
	public function get( $name ) {
		if ( is_null( $this->data ) ) {
			return $this->class::get_val( $name, $this->post );
		}

		return array_key_exists( $name, $this->data )
			? $this->data[ $name ]
			: null;
	}

	/**
	 * @param mixed ...$fields
	 *
	 * @return mixed|null
	 */
	public function get_sub( ...$fields ) {
		return $this->get( implode( '_', $fields ) );
	}
}
