<?php namespace TrevorWP\CPT;

use TrevorWP\Main;
use TrevorWP\Util\Tools;
use TrevorWP\Theme\ACF\Field_Group;
use TrevorWP\Theme\ACF\Util\Field_Val_Getter;

class Team {
	const POST_TYPE = Main::POST_TYPE_PREFIX . 'team';

	const TAXONOMY_GROUP = self::POST_TYPE . '_group';

	/**
	 * @see \TrevorWP\Util\Hooks::register_all()
	 */
	public static function construct(): void {
		add_action( 'init', [ self::class, 'init' ], 10, 0 );
	}

	/**
	 * Fires after WordPress has finished loading but before any headers are sent.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/init/
	 */
	public static function init(): void {
		register_post_type( self::POST_TYPE, [
			'public'              => false,
			'hierarchical'        => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_rest'        => true,
			'has_archive'         => false,
			'rewrite'             => false,
			'supports'            => [
				'title',
				'editor',
				'thumbnail',
				'custom-fields',
			],
			'labels'              => [
				'name'          => 'Team',
				'singular_name' => 'Team Member',
			],
		] );

		# Taxonomies
		register_taxonomy( self::TAXONOMY_GROUP, [ self::POST_TYPE ], [
			'public'            => false,
			'hierarchical'      => false,
			'show_ui'           => true,
			'show_in_rest'      => true,
			'show_tagcloud'     => false,
			'show_admin_column' => true,
			'labels'            => Tools::gen_tax_labels( 'Group' ),
		] );
	}


	/**
	 * @param \WP_Post $post
	 *
	 * @return string
	 */
	public static function render_modal( \WP_Post $post, array $options = [] ): string {
		$group_terms = get_the_terms( $post, self::TAXONOMY_GROUP );
		$group = array_pop( $group_terms )->name;
		$is_founder = false;

		$val = new Field_Val_Getter( Field_Group\Team_Member::class, $post );
		$pronoun = $val->get( Field_Group\Team_Member::FIELD_PRONOUN );
		$location = $val->get( Field_Group\Team_Member::FIELD_LOCATION );

		$options = array_merge( array_fill_keys( [
			'thumbnail',
			'is_placeholder_thumbnail',
		], [] ), $options );

		if ( strtolower( $group ) === 'founder' ) {
			$group .= ', The Trevor Project';
			$is_founder = true;
		}

		ob_start();
		?>
		<div class="team-member container mx-auto text-teal-dark bg-white text-left xl:pl-14 xl:pr-28">
			<h2 class="font-semibold w-full text-px32 leading-px42 md:text-px30 md:leading-px40 xl:text-px40 xl:leading-px48 mb-px16"><?php echo esc_html( $post->post_title ); ?></h2>
			<div class="team-member__details mb-px30 md:mb-px44">
				<?php if ( ! empty( $group ) ) { ?>
					<span class="team-member__group font-semibold text-px18 leading-px26">
						<?php echo esc_html( $group ); ?>
					</span>
				<?php } ?>
				<?php if ( ! empty( $location ) ) { ?>
					<span class="team-member__location font-normal text-px16 leading-px22"><?php echo esc_html( $location ); ?></span>
				<?php } ?>
				<?php if ( ! empty( $pronoun ) ) { ?>
					<span class="team-member__pronoun font-normal text-px16 leading-px22"><?php echo esc_html( $pronoun ); ?></span>
				<?php } ?>
			</div>
			<?php if ( ! empty( $options[ 'thumbnail' ] ) && ! $is_founder ) { ?>
				<div class="team-member__thumbnail-wrap <?php echo $options[ 'is_placeholder_thumbnail' ] ? 'placeholder' : '' ?> bg-gray-light mb-px28">
					<?php echo $options[ 'thumbnail' ];  ?>
				</div>
			<?php } ?>
			<div class="prose prose-teal-dark">
				<?= apply_filters( 'the_content', get_the_content( null, false, $post ) ); ?>
			</div>
		</div>
		<?php return ob_get_clean();
	}
}
