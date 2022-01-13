<?php namespace TrevorWP\Theme\Helper;

use TrevorWP\Theme\ACF\Options_Page\Donation_Modal;

class DonationModal extends Modal {
	const ID = 'js-donation-modal';

	public function __construct( string $content, array $options = array() ) {
		parent::__construct( $content, $options );

		$this->_selector = static::ID;
	}

	static public function create( $content_options = array() ): void {
		$content = Donation_Modal::render( $content_options );
		$options = array(
			'target'     => '.js-donation-modal',
			'dedication' => $content_options['dedication'],
			'title'      => 'Donation Form Modal',
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
