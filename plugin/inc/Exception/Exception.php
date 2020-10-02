<?php namespace TrevorWP\Exception;

use WP_Error;

/**
 * Abstract Exception
 */
abstract class Exception extends \Exception {
	/**
	 * @var WP_Error|null
	 */
	protected $_wp_error;

	/**
	 * @param string|WP_Error $message
	 * @param int $code
	 * @param \Exception|null $previous
	 */
	public function __construct( $message = "", $code = 0, \Exception $previous = null ) {
		if ( is_wp_error( $message ) ) {
			/** @var WP_Error $message */
			$message = $message->get_error_message();

			$this->_wp_error = $message;
		}
		parent::__construct( $message, $code, $previous );
	}

	/**
	 * @param mixed $var
	 *
	 * @return bool
	 */
	static public function throw_if_wp_error( $var ) {
		if ( is_wp_error( $var ) ) {
			$self = get_called_class();
			throw new $self( $var );
		}

		return false;
	}

	/**
	 * @return null|WP_Error
	 */
	public function get_wp_error() {
		return $this->_wp_error;
	}

	/**
	 * @return bool
	 */
	public function is_wp_error() {
		return ! empty( $this->_wp_error );
	}

	/**
	 * wp_die() wrapper.
	 */
	protected function _wp_die_wrap() {
		if ( ! defined( 'TREVOR_NO_WP_DIE_EXCEPTION' ) || ! constant( 'TREVOR_NO_WP_DIE_EXCEPTION' ) ) {
			call_user_func_array( 'wp_die', func_get_args() );
		}
	}
}
