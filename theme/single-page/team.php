<?php /* Team */

use \TrevorWP\Theme\Single_Page\Team as Page;

$founder_ids    = wp_parse_id_list( Page::get_val( Page::SETTING_FOUNDERS_LIST ) );
$board_ids      = wp_parse_id_list( Page::get_val( Page::SETTING_BOARD_LIST ) );
$staff_featured = wp_parse_id_list( Page::get_val( Page::SETTING_STAFF_LIST ) );
$staff_ids      = $staff_featured + ( new WP_Query( [
				'post_type'    => \TrevorWP\CPT\Team::POST_TYPE,
				'post_status'  => 'publish',
				'post__not_in' => array_merge( $staff_featured, $founder_ids, $board_ids ),
				'fields'       => 'ids',
		] ) )->posts;

$placeholder_img = Page::get_val( Page::SETTING_GENERAL_PLACEHOLDER_IMG );
?>

<?php get_header(); ?>

	<main id="site-content" role="main" class="site-content">
		<?php # Header ?>
		<?= Page::get_component( Page::SECTION_HEADER )->render( [
			/* todo: bg orange */
		] ) ?>

		<div class="bg-white text-teal-dark">
			<div class="container mx-auto">
				<?php # History ?>
				<?php ob_start() ?>
				<div>
					TODO: Video: <?= var_export( Page::get_val( Page::SETTING_HISTORY_VIDEO ), true ) ?>
				</div>
				<?= Page::get_component( Page::SECTION_HISTORY )->render( [
						'content'   => ob_get_clean(),
						'title_cls' => [ 'centered' ],
						'desc_cls'  => [ 'centered' ],
				] ) ?>

				<?php # Founders ?>
				<?php ob_start() ?>
				<div>
					<?php if ( ! empty( $founder_ids ) ):
						foreach (
								( new \WP_Query(
										[
												'post_type'      => \TrevorWP\CPT\Team::POST_TYPE,
												'orderby'        => 'include',
												'include'        => $founder_ids,
												'posts_per_page' => - 1,
												'post_status'    => 'publish'
										]
								) )->posts as $founder
						):?>
							<div class="flex w-full md:w-1/3">
								<?= $founder->post_title ?>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
				<?= Page::get_component( Page::SECTION_FOUNDERS )->render( [
						'content'   => ob_get_clean(),
						'title_cls' => [ 'centered' ],
						'desc_cls'  => [ 'centered' ],
				] ) ?>

				<?php # Board ?>
				<?php ob_start() ?>
				<div>
					<?php if ( ! empty( $board_ids ) ):
						foreach (
								( new \WP_Query(
										[
												'post_type'      => \TrevorWP\CPT\Team::POST_TYPE,
												'orderby'        => 'include',
												'include'        => $board_ids,
												'posts_per_page' => - 1,
												'post_status'    => 'publish'
										]
								) )->posts as $board_member
						):?>
							<div class="flex w-full md:w-1/3">
								<?= $board_member->post_title ?>
								<?= TrevorWP\Meta\Post::get_pronounces( $board_member->ID ) ?>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
				<?= Page::get_component( Page::SECTION_BOARD )->render( [
						'content'   => ob_get_clean(),
						'title_cls' => [ 'centered' ],
						'desc_cls'  => [ 'centered' ],
				] ) ?>

				<?php # Staff ?>
				<?php ob_start() ?>
				<div>
					<?php if ( ! empty( $staff_ids ) ):
						foreach (
								( new \WP_Query(
										[
												'post_type'      => \TrevorWP\CPT\Team::POST_TYPE,
												'orderby'        => [ 'include', ],
												'include'        => $staff_ids,
												'posts_per_page' => - 1,
										]
								) )->posts as $staff_member
						):?>
							<div class="flex w-full md:w-1/3">
								<?= $staff_member->post_title ?>
								<?= TrevorWP\Meta\Post::get_pronounces( $staff_member->ID ) ?>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
					<?php /* todo: Render all, show the first 8, make it visible the rest when the View More clicked. */ ?>
				</div>
				<?= Page::get_component( Page::SECTION_BOARD )->render( [
						'content'   => ob_get_clean(),
						'title_cls' => [ 'centered' ],
						'desc_cls'  => [ 'centered' ],
				] ) ?>
			</div>
		</div>
	</main>

<?php get_footer();
