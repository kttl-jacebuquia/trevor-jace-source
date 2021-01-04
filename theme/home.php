<?php get_header(); ?>

<main id="site-content" role="main">
	<div class="container mx-auto text-center">
		<span class="text-xl">WIP HOME PAGE</span>

		<hr class="my-10">

		<p>
			<a class="text-xl font-bold text-white"
			   href="<?= esc_attr( home_url( \TrevorWP\CPT\RC\RC_Object::PERMALINK_BASE ) ) ?>">Click here to go to the Resources Center</a>
		</p>

	</div>
</main> <!-- #site-content -->

<?php get_footer(); ?>
