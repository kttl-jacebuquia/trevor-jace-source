<?php namespace TrevorWP\Exception;

use TrevorWP\Util\Tools;

/**
 * Internal Error
 *
 * Indicates that there is an error with the application process. Probably a bug or wrong configuration. Always logs the
 * exception details. If WP_DEBUG is false then stops with wp_die() and logs the exception and does not shows the real
 * error message to visitor.
 */
class Internal extends Exception {
	/**
	 * Detailed message.
	 *
	 * @var string
	 */
	public $detail_message;

	/**
	 * Additional data to identify problem.
	 *
	 * @var array|null
	 */
	public $data;

	/**
	 * Internal constructor.
	 *
	 * @param string|\WP_Error $message
	 * @param array|null $data
	 * @param string|null $detail_message
	 * @param int $code
	 * @param \Exception|null $previous
	 */
	function __construct( $message, $data = null, $detail_message = null, $code = 0, \Exception $previous = null ) {
		parent::__construct( $message, $code, $previous );
		$this->detail_message = $detail_message;
		$this->data           = $data;

		Tools::log_exception( $this );

		if ( ! WP_DEBUG ) {
			# Stop with wp_die to not show a ugly page to visitor.
			$this->_wp_die_wrap( 'Internal Error', 'An unknown error has occurred.' );
		}
	}
}
