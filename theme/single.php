<?php get_header(); ?>

<?php
$others = array_merge(
		get_posts( [
				'numberposts' => 2,
				'exclude'     => get_the_ID(),
				'post_type'   => 'post',
				'orderby'     => 'rand'
		] ),
		get_posts( [
				'numberposts' => 2,
				'exclude'     => get_the_ID(),
				'post_type'   => TrevorWP\CPT\Support_Post::POST_TYPE,
				'orderby'     => 'rand'
		] )
);
shuffle($others);
?>

<main id="site-content" role="main">
	<?php

	if ( ! empty( $others ) ) {
		echo '<ul>';
		echo '<li><h3>Other Blog Posts:</h3></li>';
		foreach ( $others as $other ) {
			$pto   = get_post_type_object( $other->post_type );
			$plink = get_permalink( $other );
			echo '<li>';
			echo "<a href='{$plink}'><strong>[{$pto->name}]</strong> {$other->post_title}</a>";
			echo '</li>';
		}
		echo '</ul>';
	}

	get_template_part( 'template-parts/content', get_post_type() );
	?>
</main> <!-- #site-content -->

<?php get_footer(); ?>
