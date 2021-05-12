<?php namespace TrevorWP\Theme\Customizer;


class Research_Briefs extends Abstract_Customizer {
	/* Panels */
	const SECTION_ID = self::ID_PREFIX . 'research_briefs';

	/**
	 * Settings
	 */
	const SETTING_PREFIX       = self::SECTION_ID . '_';
	const SETTING_SLUG         = self::SETTING_PREFIX . 'slug';
	const SETTING_HEADER_TITLE = self::SETTING_PREFIX . 'title';
	const SETTING_HEADER_DESC  = self::SETTING_PREFIX . 'desc';
	const SETTING_PER_PAGE     = self::SETTING_PREFIX . 'per_page';

	/* All Defaults */
	const DEFAULTS = array(
		self::SETTING_SLUG         => 'research-briefs',
		self::SETTING_HEADER_TITLE => 'Our Latest Research Briefs',
		self::SETTING_HEADER_DESC  => 'Lobortis a, interdum ac semper. Vitae feugiat pulvinar nibh adipiscing fermentum accumsan, leo turpis. Cras pellentesque phasellus sit diam proin. Consequat cras sed pharetra mi tristique.',
		self::SETTING_PER_PAGE     => 6,
	);

	/** @inheritDoc */
	protected function _register_sections(): void {
		$this->_manager->add_section(
			self::SECTION_ID,
			array(
				'title' => 'Research Briefs',
			)
		);
	}

	/** @inheritDoc */
	protected function _register_controls(): void {
		$manager = $this->get_manager();

		$manager->add_control(
			self::SETTING_HEADER_TITLE,
			array(
				'setting' => self::SETTING_HEADER_TITLE,
				'section' => self::SECTION_ID,
				'label'   => 'Header Title',
				'type'    => 'text',
			)
		);

		$manager->add_control(
			self::SETTING_HEADER_DESC,
			array(
				'setting' => self::SETTING_HEADER_DESC,
				'section' => self::SECTION_ID,
				'label'   => 'Header Description',
				'type'    => 'text',
			)
		);

		$this->_manager->add_control(
			self::SETTING_PER_PAGE,
			array(
				'setting' => self::SETTING_PER_PAGE,
				'section' => self::SECTION_ID,
				'label'   => 'Per Page',
				'type'    => 'number',
			)
		);
	}
}
