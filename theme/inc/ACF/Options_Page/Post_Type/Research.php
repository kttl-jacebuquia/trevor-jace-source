<?php namespace TrevorWP\Theme\ACF\Options_Page\Post_Type;


class Research extends A_Post_Type {
	const POST_TYPE = \TrevorWP\CPT\Research::POST_TYPE;
	const SLUG      = \TrevorWP\CPT\Research::SLUG;

	const OTHER_FIELDS = array(
		'sort',
		'pagination_type',
	);
}
