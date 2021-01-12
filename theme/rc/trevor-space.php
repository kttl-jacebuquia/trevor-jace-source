<?php /* Resources Center: Trevorspace */ ?>
<?php get_header(); ?>
<?php

use TrevorWP\Theme\Customizer\Resource_Center;
use \TrevorWP\Theme\Helper\Circulation_Card;

?>
<main id="site-content" role="main" class="site-content">
	<div class="content mx-auto">
		<h1><?= Resource_Center::get_val( Resource_Center::SETTING_TREVORSPACE_TITLE ) ?></h1>
		<p><?= Resource_Center::get_val( Resource_Center::SETTING_TREVORSPACE_DESC ) ?></p>
	</div>


	<div>
		<!-- Looking for another kind of support?-->
		<!-- Explore answers and information across a variety of topics, or connect to one of our trained counselors to receive immediate support.-->

		<div class="grid">
			<?= Circulation_Card::render_get_help(); ?>
			<?= Circulation_Card::render_rc(); ?>
		</div>
	</div>
</main>

<?php get_footer(); ?>
