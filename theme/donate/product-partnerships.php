<?php /* Donate: Product Partnerships */ ?>
<?php get_header(); ?>
<?php

use \TrevorWP\Theme\Helper;
use \TrevorWP\Theme\Customizer\Product_Partnerships;

?>
	<main id="site-content" role="main" class="site-content">
		<?= Helper\Page_Header::text( [
				'title_top' => Product_Partnerships::get_val( Product_Partnerships::SETTING_HOME_HERO_TITLE_TOP ),
				'title'     => Product_Partnerships::get_val( Product_Partnerships::SETTING_HOME_HERO_TITLE ),
				'desc'      => Product_Partnerships::get_val( Product_Partnerships::SETTING_HOME_HERO_DESC ),
				'cta_txt'   => Product_Partnerships::get_val( Product_Partnerships::SETTING_HOME_HERO_CTA ),
		] ) ?>
	</main>
<?php get_footer();
