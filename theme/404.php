<?php

use TrevorWP\Theme\ACF\Options_Page\Four_O_Four;

$four_o_four_data = Four_O_Four::get_four_o_four();
?>
<?php get_header(); ?>

<main id="site-content" class="site-content" role="main">
	<div class="container mx-auto">
		<div class="error-page">
			<div class="error-page__content">
				<?php if ( ! empty( $four_o_four_data['headline'] ) ) : ?>
					<h1><?php echo esc_html( $four_o_four_data['headline'] ); ?></h1>
				<?php endif; ?>

				<?php if ( ! empty( $four_o_four_data['description'] ) ) : ?>
					<p><?php echo esc_html( $four_o_four_data['description'] ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $four_o_four_data['link']['url'] ) ) : ?>
					<a href="<?php echo esc_url( $four_o_four_data['link']['url'] ); ?>" target="<?php echo esc_attr( $four_o_four_data['link']['target'] ); ?>"><?php echo esc_html( $four_o_four_data['link']['title'] ); ?></a>
				<?php endif; ?>
			</div>
			<div class="error-page__background">
				<?php if ( ! empty( $four_o_four_data['image']['url'] ) ) : ?>
					<img
						src="<?php echo esc_url( $four_o_four_data['image']['url'] ); ?>"
						alt="<?php echo esc_attr( $four_o_four_data['image']['alt'] ); ?>"
					/>
				<?php endif; ?>
			</div>
		</div>
	</div>
</main> <!-- #site-content -->

<?php get_footer(); ?>
