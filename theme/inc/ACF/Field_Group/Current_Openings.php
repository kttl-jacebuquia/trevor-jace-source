<?php

namespace TrevorWP\Theme\ACF\Field_Group;

class Current_Openings extends A_Field_Group implements I_Block, I_Renderable {

	const FIELD_HEADLINE    = 'headline';
	const FIELD_DESCRIPTION = 'description';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$headline    = static::gen_field_key( static::FIELD_HEADLINE );
		$description = static::gen_field_key( static::FIELD_DESCRIPTION );

		return array(
			'title'  => 'Current Openings',
			'fields' => array(
				static::FIELD_HEADLINE    => array(
					'key'   => $headline,
					'name'  => static::FIELD_HEADLINE,
					'label' => 'Headline',
					'type'  => 'text',
				),
				static::FIELD_DESCRIPTION => array(
					'key'          => $description,
					'name'         => static::FIELD_DESCRIPTION,
					'label'        => 'Description',
					'type'         => 'wysiwyg',
					'toolbar'      => 'basic',
					'media_upload' => 0,
				),
			),
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function get_block_args(): array {
		return array_merge(
			parent::get_block_args(),
			array(
				'name'       => static::get_key(),
				'title'      => 'Current Openings',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$headline    = static::get_val( static::FIELD_HEADLINE );
		$description = static::get_val( static::FIELD_DESCRIPTION );

		ob_start();
		?>

		<div id="<?php echo esc_attr( static::get_key() ); ?>" class="current-openings">
			<div class="current-openings__container">
				<div class="current-openings__header">
					<h2 class="current-openings__headline"><?php echo esc_html( $headline ); ?></h2>
					<?php if ( ! empty( $description ) ) : ?>
					<div class="current-openings__description"><?php echo $description; ?></div>
					<?php endif; ?>
				</div>

				<div class="current-openings__content">
					<div class="listing js-current-openings"
						 data-endpoint="http://trevor-web.lndo.site/wp-admin/admin-ajax.php?action=adp">
						<div class="listing__header">
							<?php echo static::render_filter_navigation(); ?>
							<div class="listing__info"></div>
						</div>
						<div class="listing__content"></div>
						<div class="listing__footer">
							<a href="#<?php echo esc_attr( static::get_key() ); ?>">Back to Top</a>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php
		return ob_get_clean();
	}

	/**
	 * @inheritDoc
	 */
	public static function render_filter_navigation(): ?string {
		ob_start();
		?>
		<ul class="filters filters--current-openings"
			role="menubar"
			aria-label="<?php echo esc_html( $headline ); ?>">
			<li class="filter" role="none">
				<button class="filter__header"
						role="menuitem"
						aria-haspopup="true"
						aria-expanded="false"
						aria-label="All Locations"
						type="button">
					<span>Locations</span>
					<i class="trevor-ti-caret-down"></i>
				</button>

				<div class="filter__content">
					<ul class="filter__navigation"
						role="menu"
						data-option-group="locations"
						aria-label="Locations">
						<li class="filter__navigation__item"
							data-option-value="all-locations"
							role="menuitemcheckbox"
							aria-checked="true">
							All Locations
						</li>
					</ul>
				</div>
			</li>

			<li class="filter" role="none">
				<button class="filter__header"
						role="menuitem"
						aria-haspopup="true"
						aria-expanded="false"
						aria-label="All Departments"
						type="button">
					<span>Departments</span>
					<i class="trevor-ti-caret-down"></i>
				</button>
				<div class="filter__content">
					<ul class="filter__navigation"
						role="menu"
						data-option-group="departments"
						aria-label="Departments">
						<li class="filter__navigation__item"
							data-option-value="all-departments"
							role="menuitemcheckbox"
							aria-checked="true">
							All Departments
						</li>
					</ul>
				</div>
			</li>
		</ul>

		<?php
		return ob_get_clean();
	}

	/**
	 * @inheritDoc
	 */
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		echo static::render( $post_id, null, compact( 'is_preview' ) );
	}
}
