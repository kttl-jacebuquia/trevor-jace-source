<?php /* Resources Center: Get Help */ ?>
<?php get_header(); ?>
<?php

use TrevorWP\Theme\Customizer\Resource_Center;

?>
<main id="site-content" role="main" class="site-content">
	<div class="content mx-auto">
		<h1><?= Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_1_TITLE ) ?></h1>
		<p><?= Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_1_DESC ) ?></p>
	</div>
</main>

<?php get_footer(); ?>
