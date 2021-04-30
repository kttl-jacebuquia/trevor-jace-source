<?php namespace TrevorWP\Theme\ACF\Options_Page\Post_Type;


class Post extends A_Post_Type {
	const POST_TYPE = \TrevorWP\CPT\Post::POST_TYPE;

	/** @inheritdoc */
	protected static function prepare_page_register_args(): array {
		return array_merge( parent::prepare_page_register_args(), [
			'parent_slug' => 'edit.php',
		] );
	}
}
