<?php namespace TrevorWP\Exception;

/**
 * Unauthorized Error
 */
class Unauthorized extends Exception {
	/** @inheritdoc */
	public function __construct( $message = "", $code = 0, \Exception $previous = null ) {
		parent::__construct( $message, $code, $previous );

		if ( empty( $message ) ) {
			$message = 'You do not have permission to perform this action.';
		}
		$this->_wp_die_wrap( $message, 'Unauthorized!' );
	}

	/**
	 * @param string $capability Capability name.
	 * @param int|\WP_User|null $user User ID or object.
	 * @param string|null $msg Optional message.
	 *
	 * @throws Unauthorized
	 */
	static public function throw_if_cant( $capability, $user = null, $msg = null ) {

		if ( is_null( $user ) ) {
			$user = wp_get_current_user();
		}

		if ( ! user_can( $user, $capability ) ) {
			throw new self( $msg );
		}
	}
}
