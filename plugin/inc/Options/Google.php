<?php namespace TrevorWP\Options;


use TrevorWP\Main;

class Google {
	const KEY_PREFIX = Main::OPTION_KEY_PREFIX . 'g_api_';

	/*
	 * Keys
	 */
	const KEY_ACCESS_KEY = self::KEY_PREFIX . 'access_token';
	const KEY_GA_VIEW_ID = self::KEY_PREFIX . 'ga_view_id';
	const KEY_GA_PAGE_VIEW_TO = self::KEY_PREFIX . 'ga_page_view_to';

	/* Defaults */
	const DEFAULTS = [
		self::KEY_GA_PAGE_VIEW_TO => 10
	];
}
