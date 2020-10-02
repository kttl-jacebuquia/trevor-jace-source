<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	<h2 class="text-blue-dark"><a href="<?= get_the_permalink() ?>"><?php the_title(); ?></a></h2>

	<div class="text-black"> <?php the_content(); ?> </div>

</article><!-- .post -->
