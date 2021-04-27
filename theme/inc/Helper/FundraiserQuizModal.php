<?php namespace TrevorWP\Theme\Helper;

use TrevorWP\Theme\Helper\Modal;

class FundraiserQuizModal extends Modal {

	public function __construct( string $content, array $options = [] ) {
		parent::__construct( $content, $options );

		$this->_selector = 'js-fundraiser-quiz';
	}
}
