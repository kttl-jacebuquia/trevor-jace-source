<?php namespace TrevorWP\Theme\Helper;

use TrevorWP\Theme\ACF\Options_Page\What_To_Expect;

class WhatToExpectModal extends Modal {
	const ID = 'js-what-to-expect-modal';

	static public $rendered = false;

	public function __construct( string $content, array $options = array() ) {
		$options =

		parent::__construct( $content, $options );

		$this->_selector = static::ID;
	}

	static public function create( $content_options = array() ): void {
		// Render only once
		if ( static::$rendered ) {
			return;
		}

		$content = What_To_Expect::render();
		$options = array(
			'target' => '.js-what-to-expect-modal',
			'class'  => array( 'what-to-expect-modal' ),
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
