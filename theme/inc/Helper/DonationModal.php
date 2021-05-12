<?php namespace TrevorWP\Theme\Helper;

class DonationModal extends Modal {

	public function __construct( string $content, array $options = array() ) {
		parent::__construct( $content, $options );

		$this->_selector = 'js-donation-modal';
	}
}
