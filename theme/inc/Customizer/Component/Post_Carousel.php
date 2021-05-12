<?php namespace TrevorWP\Theme\Customizer\Component;

use TrevorWP\Theme\Helper;

class Post_Carousel extends Section {
	/** @inheritDoc */
	public function register_section( array $args = array() ): void {
		parent::register_section( array_merge( array( 'title' => 'Post Carousel' ), $args ) );
	}

	/** @inheritdoc */
	public function render( array $ext_options = array() ): string {
		$posts = (array) @$ext_options['posts']; // required
		unset( $ext_options['posts'] );

		return Helper\Carousel::posts(
			$posts,
			array_merge(
				$this->_options,
				array(
					'title'    => $this->get_val( $this::SETTING_TITLE ),
					'subtitle' => $this->get_val( $this::SETTING_DESC ),
				),
				$ext_options
			)
		);
	}
}
