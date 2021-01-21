<?php namespace TrevorWP\CPT\Get_Involved;


class Corp_Partner extends Get_Involved_Object {
	/* Flags */
	const IS_PUBLIC = false;

	const POST_TYPE = self::POST_TYPE_PREFIX . 'corp_partner';

	/** @inheritDoc */
	static function register_post_type(): void {
		// TODO: Implement register_post_type() method.
	}
}
