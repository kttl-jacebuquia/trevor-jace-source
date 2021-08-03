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

		// Note: This is a dummy data
		$filter_list  = static::get_filter_list();
		$job_listings = static::get_opened_jobs();

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
					<div class="listing js-current-openings">
						<div class="listing__header">

							<?php echo static::render_filter_navigation( $filter_list ); ?>

							<div class="listing__info">
								Currently viewing <span><?php echo count( $job_listings ); ?> jobs</span>
							</div>
						</div>

						<div class="listing__content">
							<?php echo static::render_job_listing_items( $job_listings ); ?>
						</div>

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
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		echo static::render( $post_id, null, compact( 'is_preview' ) );
	}

	/**
	 * @inheritDoc
	 */
	public static function render_filter_navigation( $filter_list ): ?string {
		ob_start();
		?>

		<ul class="filters filters--current-openings"
			role="menubar"
			aria-label="<?php echo esc_html( $headline ); ?>">

			<?php foreach ( $filter_list as $filter_code => $filter ) : ?>
				<?php $button_label = "All {$filter['label']}"; ?>
				<li class="filter" role="none">
					<button class="filter__header"
							role="menuitem"
							aria-haspopup="true"
							aria-expanded="false"
							aria-label="<?php echo esc_attr( $button_label ); ?>"
							type="button">
						<span><?php echo esc_html( $button_label ); ?></span>
						<i class="trevor-ti-caret-down"></i>
					</button>
					<div class="filter__content">
						<ul class="filter__navigation"
							role="menu"
							data-option-group="<?php echo esc_attr( $filter_code ); ?>"
							aria-label="<?php echo esc_html( $filter['label'] ); ?>">
							<li class="filter__navigation__item"
								data-option-value="all-<?php echo esc_attr( $filter_code ); ?>"
								role="menuitemcheckbox"
								aria-checked="true">
								<?php echo esc_html( $button_label ); ?>
							</li>

							<?php foreach ( $filter['items'] as $item ) : ?>
								<li class="filter__navigation__item"
									data-option-value="<?php echo esc_attr( $item['code'] ); ?>"
									role="menuitemcheckbox"
									aria-checked="false">
									<?php echo esc_html( $item['label'] ); ?>
								</li>
							<?php endforeach; ?>

						</ul>
					</div>
				</li>
			<?php endforeach; ?>

		</ul>

		<?php
		return ob_get_clean();
	}

	/**
	 * @inheritDoc
	 */
	public static function render_job_listing_items( $job_listings ): ?string {
		ob_start();
		?>
		<?php foreach ( $job_listings as $job ) : ?>
			<?php
			$classes = array( 'show' );
			if ( $job['location'] ) {
				$classes[] = $job['location']['code'];
			}
			if ( $job['department'] ) {
				$classes[] = $job['department']['code'];
			}

			?>
		<div class="listing__item <?php echo esc_attr( implode( ' ', $classes ) ); ?>">
			<div class="listing__item-inner">
				<p class="listing__item__eyebrow">
					<?php if ( $job['department'] ) : ?>
						<span><?php echo esc_html( $job['department']['label'] ); ?></span>
					<?php endif; ?>
					<?php if ( $job['location'] ) : ?>
						<span><?php echo esc_html( $job['location']['label'] ); ?></span>
					<?php endif; ?>
				</p>
				<h3 class="listing__item__title">
					<?php echo esc_html( $job['title'] ); ?>
					<span>(<?php echo esc_html( $job['work_type'] ); ?>)</span>
				</h3>
				<time class="listing__item__date"><?php echo esc_html( $job['post_date'] ); ?></time>
			</div>
			<div class="listing__item__cta">
				<a href="<?php echo esc_url( $job['post_link'] ); ?>">Apply Now</a>
			</div>
		</div>
		<?php endforeach; ?>
		<?php
		return ob_get_clean();
	}

	/**
	 * Note: for demo purposes only
	 */
	public static function get_filter_list(): ?array {
		return array(
			'locations'   => array(
				'label' => 'Locations',
				'items' => array(
					array(
						'label' => 'Flexible',
						'code'  => 'flexible',
					),
					array(
						'label' => 'Remote',
						'code'  => 'remote',
					),
				),
			),
			'departments' => array(
				'label' => 'Departments',
				'items' => array(
					array(
						'label' => 'Clerical',
						'code'  => '0001',
					),
					array(
						'label' => 'Professional',
						'code'  => '0002',
					),
					array(
						'label' => 'Sales',
						'code'  => '0003',
					),
				),
			),
		);
	}

	/**
	 * Note: for demo purposes only
	 */
	public static function get_opened_jobs(): ?array {
		return array(
			array(
				'department' => array(
					'label' => 'Clerical',
					'code'  => '0001',
				),
				'location'   => array(
					'label' => 'Remote',
					'code'  => 'remote',
				),
				'title'      => 'Overnight Text & Chat Counselor',
				'work_type'  => 'Full Time',
				'post_date'  => '1 day ago',
				'post_link'  => '#',
			),
			array(
				'department' => array(
					'label' => 'Sales',
					'code'  => '0003',
				),
				'location'   => array(
					'label' => 'Flexible',
					'code'  => 'flexible',
				),
				'title'      => 'Corporate Development Manager',
				'work_type'  => 'Full Time',
				'post_date'  => '1 day ago',
				'post_link'  => '#',
			),
			array(
				'department' => array(),
				'location'   => array(
					'label' => 'Flexible',
					'code'  => 'flexible',
				),
				'title'      => 'Volunteer Recruitment Coordinator',
				'work_type'  => 'Full Time',
				'post_date'  => '1 day ago',
				'post_link'  => '#',
			),
			array(
				'department' => array(
					'label' => 'Professional',
					'code'  => '0002',
				),
				'location'   => array(
					'label' => 'Remote',
					'code'  => 'remote',
				),
				'title'      => 'Product Manager',
				'work_type'  => 'Full Time',
				'post_date'  => '2 days ago',
				'post_link'  => '#',
			),
			array(
				'department' => array(),
				'location'   => array(
					'label' => 'Remote',
					'code'  => 'remote',
				),
				'title'      => 'Performance Development Manager',
				'work_type'  => 'Full Time',
				'post_date'  => '3 days ago',
				'post_link'  => '#',
			),
			array(
				'department' => array(),
				'location'   => array(
					'label' => 'Flexible',
					'code'  => 'flexible',
				),
				'title'      => 'eLearning Developer',
				'work_type'  => 'Full Time',
				'post_date'  => '3 days ago',
				'post_link'  => '#',
			),
			array(
				'department' => array(),
				'location'   => array(
					'label' => 'Remote',
					'code'  => 'remote',
				),
				'title'      => 'Crisis Chat Program Manager',
				'work_type'  => 'Full Time',
				'post_date'  => '3 days ago',
				'post_link'  => '#',
			),
			array(
				'department' => array(),
				'location'   => array(
					'label' => 'Remote',
					'code'  => 'remote',
				),
				'title'      => 'People Operations & Culture Manager',
				'work_type'  => 'Full Time',
				'post_date'  => '4 days ago',
				'post_link'  => '#',
			),
			array(
				'department' => array(),
				'location'   => array(
					'label' => 'Flexible',
					'code'  => 'flexible',
				),
				'title'      => 'Technology Support Specialist',
				'work_type'  => 'Full Time',
				'post_date'  => '5 days ago',
				'post_link'  => '#',
			),
			array(
				'department' => array(),
				'location'   => array(
					'label' => 'Remote',
					'code'  => 'remote',
				),
				'title'      => 'Recruiter',
				'work_type'  => 'Full Time',
				'post_date'  => '5 days ago',
				'post_link'  => '#',
			),
		);
	}
}
