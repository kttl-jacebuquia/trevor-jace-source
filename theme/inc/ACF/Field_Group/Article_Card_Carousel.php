<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Classy\Content;
use TrevorWP\CPT\Event;
use TrevorWP\CPT\RC\RC_Object;
use TrevorWP\CPT\Research;
use TrevorWP\Theme\Helper;

use TrevorWP\Theme\ACF\Field\Color;
use TrevorWP\Theme\ACF\Field_Group;

class Article_Card_Carousel extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_BG_COLOR        = 'bg_color';
	const FIELD_TEXT_COLOR      = 'text_color';
	const FIELD_TITLE           = 'title';
	const FIELD_DESCRIPTION     = 'description';
	const FIELD_PLACEHOLDER_IMG = 'placeholder';
	const FIELD_CARD_TYPE       = 'card_type';
	const FIELD_RESOURCES       = 'resources';
	const FIELD_BLOGS           = 'blogs';
	const FIELD_RESEARCH        = 'research';
	const FIELD_BUTTON          = 'button';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$bg_color        = static::gen_field_key( static::FIELD_BG_COLOR );
		$text_color      = static::gen_field_key( static::FIELD_TEXT_COLOR );
		$title           = static::gen_field_key( static::FIELD_TITLE );
		$description     = static::gen_field_key( static::FIELD_DESCRIPTION );
		$placeholder_img = static::gen_field_key( static::FIELD_PLACEHOLDER_IMG );
		$card_type       = static::gen_field_key( static::FIELD_CARD_TYPE );
		$resources       = static::gen_field_key( static::FIELD_RESOURCES );
		$blogs           = static::gen_field_key( static::FIELD_BLOGS );
		$research        = static::gen_field_key( static::FIELD_RESEARCH );
		$button          = static::gen_field_key( static::FIELD_BUTTON );

		return array(
			'title'  => 'Article Card Carousel',
			'fields' => array(
				static::FIELD_BG_COLOR        => Color::gen_args(
					$bg_color,
					static::FIELD_BG_COLOR,
					array(
						'label'   => 'Background Color',
						'default' => 'white',
						'wrapper' => array(
							'width' => '50%',
						),
					)
				),
				static::FIELD_TEXT_COLOR      => Color::gen_args(
					$text_color,
					static::FIELD_TEXT_COLOR,
					array(
						'label'   => 'Text Color',
						'default' => 'teal-dark',
						'wrapper' => array(
							'width' => '50%',
						),
					),
				),
				static::FIELD_TITLE           => array(
					'key'   => $title,
					'name'  => static::FIELD_TITLE,
					'label' => 'Title',
					'type'  => 'text',
				),
				static::FIELD_DESCRIPTION     => array(
					'key'   => $description,
					'name'  => static::FIELD_DESCRIPTION,
					'label' => 'Description',
					'type'  => 'textarea',
				),
				static::FIELD_PLACEHOLDER_IMG => array(
					'key'           => $placeholder_img,
					'name'          => static::FIELD_PLACEHOLDER_IMG,
					'label'         => 'Placeholder Image',
					'type'          => 'image',
					'return_format' => 'array',
					'preview_size'  => 'thumbnail',
					'library'       => 'all',
				),
				static::FIELD_CARD_TYPE       => array(
					'key'           => $card_type,
					'name'          => static::FIELD_CARD_TYPE,
					'label'         => 'Card Type',
					'type'          => 'select',
					'choices'       => array(
						'resources'            => 'Resources',
						'blogs'                => 'Blogs',
						'events_past'          => 'Past Events',
						'events_upcoming'      => 'Upcoming Events',
						'fundraise_teams'      => 'Fundraise (Top Teams)',
						'fundraise_individual' => 'Fundraise (Top Individuals)',
						'research'             => 'Research',
					),
					'default_value' => 'resources',
				),
				static::FIELD_RESOURCES       => array(
					'key'               => $resources,
					'name'              => static::FIELD_RESOURCES,
					'label'             => 'Resources',
					'type'              => 'relationship',
					'required'          => 1,
					'post_type'         => RC_Object::$PUBLIC_POST_TYPES,
					'min'               => 1,
					'max'               => 12,
					'conditional_logic' => array(
						array(
							array(
								'field'    => $card_type,
								'operator' => '==',
								'value'    => 'resources',
							),
						),
					),
				),
				static::FIELD_BLOGS           => array(
					'key'               => $blogs,
					'name'              => static::FIELD_BLOGS,
					'label'             => 'Blogs',
					'type'              => 'relationship',
					'required'          => 1,
					'post_type'         => array( 'post' ),
					'min'               => 1,
					'max'               => 12,
					'conditional_logic' => array(
						array(
							array(
								'field'    => $card_type,
								'operator' => '==',
								'value'    => 'blogs',
							),
						),
					),
				),
				static::FIELD_RESEARCH        => array(
					'key'               => $research,
					'name'              => static::FIELD_RESEARCH,
					'label'             => 'Research',
					'type'              => 'relationship',
					'required'          => 1,
					'post_type'         => array( Research::POST_TYPE ),
					'min'               => 1,
					'max'               => 12,
					'conditional_logic' => array(
						array(
							array(
								'field'    => $card_type,
								'operator' => '==',
								'value'    => 'research',
							),
						),
					),
				),
				static::FIELD_BUTTON          => array(
					'key'           => $button,
					'name'          => static::FIELD_BUTTON,
					'label'         => 'Button',
					'type'          => 'link',
					'return_format' => 'array',
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
				'title'      => 'Article Card Carousel',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$text_color      = ! empty( static::get_val( static::FIELD_TEXT_COLOR ) ) ? static::get_val( static::FIELD_TEXT_COLOR ) : 'teal-dark';
		$bg_color        = ! empty( static::get_val( static::FIELD_BG_COLOR ) ) ? static::get_val( static::FIELD_BG_COLOR ) : 'white';
		$title           = static::get_val( static::FIELD_TITLE );
		$card_type       = static::get_val( static::FIELD_CARD_TYPE );
		$button          = static::get_val( static::FIELD_BUTTON );
		$placeholder_img = static::get_val( static::FIELD_PLACEHOLDER_IMG );

		$campaign_id = 24399;

		$cards = array();

		$options = array(
			'title' => $title,
		);

		$class     = array( 'mt-12', 'mb-0', $text_color, $bg_color );
		$title_cls = array();

		if ( 'resources' === $card_type ) {
			$cards = static::get_val( static::FIELD_RESOURCES );
		} elseif ( 'blogs' === $card_type ) {
			$cards = static::get_val( static::FIELD_BLOGS );
		} elseif ( 'research' === $card_type ) {
			$cards = static::get_val( static::FIELD_RESEARCH );
		} elseif ( 'fundraise_teams' === $card_type ) {
			$cards   = Content::get_fundraising_teams( $campaign_id, 12, true );
			$class[] = 'top-teams';
		} elseif ( 'fundraise_individual' === $card_type ) {
			$cards   = Content::get_fundraisers( $campaign_id, 12, true );
			$class[] = 'top-individuals';
		} elseif ( 'events_past' === $card_type ) {
			$class[]     = 'post-carousel--event mb-px80';
			$title_cls[] = 'text-center';

			$cards = static::get_past_and_upcoming_events( 'DESC', '<' );
		} elseif ( 'events_upcoming' === $card_type ) {
			$class[]     = 'post-carousel--event mb-px80';
			$title_cls[] = 'text-center';

			$cards = static::get_past_and_upcoming_events( 'ASC', '>' );
		}

		$options['class']     = implode( ' ', $class );
		$options['title_cls'] = implode( ' ', $title_cls );

		if ( ! empty( $placeholder_img['url'] ) ) {
			$options['card_options'] = array( 'placeholder_image' => $placeholder_img );
		}

		ob_start();
		// Next Step - FE
		?>
		<div>
			<?php if ( ! empty( $cards ) ) : ?>
				<?php if ( 'fundraise_teams' === $card_type || 'fundraise_individual' === $card_type ) : ?>
					<?php echo Helper\Carousel::fundraisers( $cards, $options ); ?>
				<?php else : ?>
					<?php echo Helper\Carousel::posts( $cards, $options ); ?>
				<?php endif; ?>
			<?php endif; ?>

			<?php if ( ! empty( $button['url'] ) ) : ?>
				<a href="<?php echo esc_url( $button['url'] ); ?>" target="<?php echo esc_attr( $button['target'] ); ?>"><?php echo esc_html( $button['title'] ); ?></a>
			<?php endif; ?>
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
	 * @param string $order
	 * @param string $compare
	 * @param int $count
	 *
	 * @return WP_POST
	 */
	public static function get_past_and_upcoming_events( $order, $compare, $count = 12 ) {
		$current_date = current_datetime();
		$current_date = wp_date( 'Ymd', $current_date->date, $current_date->timezone );

		$args = array(
			'posts_per_page' => $count,
			'post_type'      => Event::POST_TYPE,
			'orderby'        => 'meta_value',
			'meta_key'       => Field_Group\Event::FIELD_DATE,
			'order'          => $order,
			'meta_query'     => array(
				'relation' => 'AND',
				array(
					'key'     => Field_Group\Event::FIELD_DATE,
					'value'   => $current_date,
					'compare' => $compare,
					'type'    => 'DATE',
				),
			),
		);

		return get_posts( $args );
	}
}
