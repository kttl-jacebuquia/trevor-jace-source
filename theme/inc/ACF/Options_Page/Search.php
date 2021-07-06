<?php namespace TrevorWP\Theme\ACF\Options_Page;

class Search extends A_Options_Page {
	const FIELD_POSTS_PER_PAGE     = 'search_posts_per_page';
	const FIELD_HEADLINE           = 'search_headline';
	const FIELD_SEARCH_PLACEHOLDER = 'search_placeholder';
	const FIELD_TITLE              = 'search_carousel_title';
	const FIELD_DESCRIPTION        = 'search_carousel_description';

	/** @inheritDoc */
	protected static function prepare_fields(): array {
		$posts_per_page     = static::gen_field_key( static::FIELD_POSTS_PER_PAGE );
		$headline           = static::gen_field_key( static::FIELD_HEADLINE );
		$search_placeholder = static::gen_field_key( static::FIELD_SEARCH_PLACEHOLDER );
		$title              = static::gen_field_key( static::FIELD_TITLE );
		$description        = static::gen_field_key( static::FIELD_DESCRIPTION );

		return array_merge(
			static::_gen_tab_field( 'Hero' ),
			array(
				static::FIELD_POSTS_PER_PAGE     => array(
					'key'           => $posts_per_page,
					'name'          => static::FIELD_POSTS_PER_PAGE,
					'label'         => 'Posts Per Page',
					'type'          => 'number',
					'required'      => true,
					'default_value' => 20,
					'min'           => 1,
					'step'          => 1,
				),
				static::FIELD_HEADLINE           => array(
					'key'      => $headline,
					'name'     => static::FIELD_HEADLINE,
					'label'    => 'Headline',
					'type'     => 'text',
					'required' => true,
				),
				static::FIELD_SEARCH_PLACEHOLDER => array(
					'key'           => $search_placeholder,
					'name'          => static::FIELD_SEARCH_PLACEHOLDER,
					'label'         => 'Search Placeholder',
					'type'          => 'text',
					'required'      => true,
					'default_value' => 'What are you looking for?',
				),
			),
			static::_gen_tab_field( 'Carousel' ),
			array(
				static::FIELD_TITLE       => array(
					'key'      => $title,
					'name'     => static::FIELD_TITLE,
					'label'    => 'Title',
					'type'     => 'text',
					'required' => true,
				),
				static::FIELD_DESCRIPTION => array(
					'key'   => $description,
					'name'  => static::FIELD_DESCRIPTION,
					'label' => 'Description',
					'type'  => 'textarea',
				),
			),
		);
	}
}
