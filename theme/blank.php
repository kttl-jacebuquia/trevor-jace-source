<?php
/*
Template Name: Empty Template (no branding or foundation)
*/
?>

<head>

	<?php wp_head(); ?>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

</head>

<body id="tech">
<?php
while ( have_posts() ) :
	the_post();
	?>
	<?php the_content(); ?>
<?php endwhile; ?>
<?php edit_post_link( __( '(Edit)', 'foundationpress' ), '<span class="edit-link">', '</span>' ); ?>
</body>
