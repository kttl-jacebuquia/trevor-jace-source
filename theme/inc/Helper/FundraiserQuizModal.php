<?php namespace TrevorWP\Theme\Helper;

use TrevorWP\Theme\ACF\Options_Page\Fundraiser_Quiz;

class FundraiserQuizModal extends Modal {
	const ID = 'js-fundraiser-quiz';

	public function __construct( string $content, array $options = array() ) {
		parent::__construct( $content, $options );

		$this->_selector = static::ID;
	}

	static public function create( $options = array() ): void {
		$content = Fundraiser_Quiz::render();
		$options = array_merge(
			array(
				'target' => '.js-fundraiser-quiz',
				'class'  => array( 'fundraiser-quiz' ),
			),
			$options
		);

		// Ensure that modals are only rendered down the document
		add_action(
			'wp_footer',
			function() use ( $content, $options ) {
				echo ( new self( $content, $options ) )->render();
			},
			10,
			0
		);
	}
}
