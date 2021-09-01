<?php namespace TrevorWP\Theme\Helper;

use TrevorWP\Theme\ACF\Field_Group\What_To_Expect_Popup;
use TrevorWP\Theme\ACF\Options_Page\What_To_Expect;

class WhatToExpectModal extends Modal {
	const ID = 'js-what-to-expect-modal';

	static public $rendered = false;

	public function __construct( string $content, array $options = array() ) {
		$options = parent::__construct( $content, $options );
	}

	public static function create( $post = null ): void {
		$id = static::ID;

		if ( ! empty( $post ) ) {
			$content = What_To_Expect_Popup::render( $post );
			$id      = What_To_Expect_Popup::gen_modal_id( $post->ID );
		} else {
			$content = What_To_Expect::render();
		}

		$options = array(
			'id'     => $id,
			'target' => '.' . $id,
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
