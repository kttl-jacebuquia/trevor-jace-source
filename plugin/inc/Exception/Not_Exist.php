<?php namespace TrevorWP\Exception;


/**
 * Not Exist Error
 */
class Not_Exist extends Exception {
	/** @inheritdoc */
	public function __construct( $message = '', $code = 0, \Exception $previous = null ) {
		if ( empty( $message ) ) {
			$message = __( 'You attempted to edit an item that doesn&#8217;t exist. Perhaps it was deleted?' );
		}

		parent::__construct( $message, $code, $previous );

		$this->_wp_die_wrap( $message, __( 'Does not exist!' ) );
	}
}
