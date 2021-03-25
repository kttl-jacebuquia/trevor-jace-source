<?php get_header(); ?>

<main id="site-content" role="main">
	<div class="container mx-auto text-center">
		<h1 class="text-xl text-white my-10">WIP HOME PAGE</h1>

		<hr class="my-10">

		<ul class="my-20">
			<li>
				<a class="text-xl font-bold text-white"
				   href="<?= esc_attr( home_url( \TrevorWP\CPT\RC\RC_Object::PERMALINK_BASE ) ) ?>">Resources Center</a>
			</li>
			<li>
				<a class="text-xl font-bold text-white"
				   href="<?= esc_attr( home_url( \TrevorWP\CPT\RC\RC_Object::PERMALINK_TREVORSPACE ) ) ?>">TrevorSpace</a>
			</li>
			<li>
				<a class="text-xl font-bold text-white"
				   href="<?= esc_attr( home_url( \TrevorWP\CPT\RC\RC_Object::PERMALINK_GET_HELP ) ) ?>">Get Help</a>
			</li>
		</ul>

		<hr class="my-10">

		<ul class="my-20">
			<li>
				<a class="text-xl font-bold text-white"
				   href="<?= esc_attr( home_url( \TrevorWP\CPT\Get_Involved\Get_Involved_Object::PERMALINK_ADVOCACY ) ) ?>">Advocacy</a>
			</li>
			<li>
				<a class="text-xl font-bold text-white"
				   href="<?= esc_attr( home_url( \TrevorWP\CPT\Get_Involved\Get_Involved_Object::PERMALINK_ECT ) ) ?>">Ending Conversion Therapy</a>
			</li>
			<li>
				<a class="text-xl font-bold text-white"
				   href="<?= esc_attr( home_url( \TrevorWP\CPT\Get_Involved\Get_Involved_Object::PERMALINK_VOLUNTEER ) ) ?>">Volunteer</a>
			</li>
			<li>
				<a class="text-xl font-bold text-white"
				   href="<?= esc_attr( home_url( \TrevorWP\CPT\Get_Involved\Get_Involved_Object::PERMALINK_PARTNER_W_US ) ) ?>">Partner with Us</a>
			</li>
			<li>
				<a class="text-xl font-bold text-white"
				   href="<?= esc_attr( home_url( \TrevorWP\CPT\Get_Involved\Get_Involved_Object::PERMALINK_CORP_PARTNERSHIPS ) ) ?>">Corporate Partnerships</a>
			</li>
			<li>
				<a class="text-xl font-bold text-white"
				   href="<?= esc_attr( home_url( \TrevorWP\CPT\Get_Involved\Get_Involved_Object::PERMALINK_INSTITUTIONAL_GRANTS ) ) ?>">Institutional Grants</a>
			</li>
		</ul>

		<hr class="my-10">

		<ul class="my-20">
			<li>
				<a class="text-xl font-bold text-white"
				   href="<?= esc_attr( home_url( \TrevorWP\CPT\Donate\Donate_Object::PERMALINK_DONATE ) ) ?>">Donate</a>
			</li>
			<li>
				<a class="text-xl font-bold text-white"
				   href="<?= esc_attr( home_url( \TrevorWP\CPT\Donate\Donate_Object::PERMALINK_FUNDRAISE ) ) ?>">Fundraise</a>
			</li>
			<li>
				<a class="text-xl font-bold text-white"
				   href="<?= esc_attr( home_url( \TrevorWP\CPT\Donate\Donate_Object::PERMALINK_PROD_PARTNERSHIPS ) ) ?>">Product Partnerships</a>
			</li>
			<li>
				<a class="text-xl font-bold text-white"
				   href="<?= esc_attr( home_url( \TrevorWP\CPT\Donate\Donate_Object::PERMALINK_PROD_PARTNERS ) ) ?>">Shop our Product Partners</a>
			</li>
		</ul>

		<hr class="my-10">

		<ul class="my-20">
			<li>
				<a class="text-xl font-bold text-white"
				   href="<?= TrevorWP\Theme\Single_Page\Public_Education::get_permalink() ?>">Public Education</a>
			</li>

			<li>
				<a class="text-xl font-bold text-white"
				   href="<?= TrevorWP\Theme\Single_Page\Ally_Training::get_permalink() ?>">Ally Training</a>
			</li>

			<li>
				<a class="text-xl font-bold text-white"
				   href="<?= TrevorWP\Theme\Single_Page\Strategic_Plan::get_permalink() ?>">Strategic Plan</a>
			</li>

			<li>
				<a class="text-xl font-bold text-white"
				   href="<?= TrevorWP\Theme\Single_Page\Careers::get_permalink() ?>">Careers</a>
			</li>

			<li>
				<a class="text-xl font-bold text-white"
				   href="<?= TrevorWP\Theme\Single_Page\Contact_Us::get_permalink() ?>">Contact Us</a>
			</li>
		</ul>

		<hr class="my-10">

		<ul class="my-20">
			<li>
				<a class="text-xl font-bold text-white"
				   href="<?= TrevorWP\Theme\Customizer\Search::get_permalink() ?>">Site-Wide Search</a>
			</li>
		</ul>
	</div>
</main> <!-- #site-content -->

<?php get_footer(); ?>
