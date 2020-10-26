<?php wp_footer();

use \TrevorWP\Theme\Customizer;
?>

<footer class="w-full h-40 bg-gray flex flex-col justify-center">
	<p class="text-white text-center text-3xl">Footer</p>
</footer>

<?= Customizer\External_Scripts::get_val( Customizer\External_Scripts::SETTING_BODY_BTM ) ?>
</body>
</html>
