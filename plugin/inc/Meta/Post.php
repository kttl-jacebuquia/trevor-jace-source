<?php namespace TrevorWP\Meta;

use TrevorWP\Main;

/**
 * Post Meta
 */
class Post {
	const KEY_VIEW_COUNT_SHORT = Main::META_KEY_PREFIX . 'uniq_views_short';
	const KEY_VIEW_COUNT_LONG = Main::META_KEY_PREFIX . 'uniq_views_long';
	const KEY_POPULARITY_RANK = Main::META_KEY_PREFIX . 'popularity_rank';
	const KEY_AVG_VISITS = Main::META_KEY_PREFIX . 'avg_visits';
}
