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
				   href="/public-education/">Public Education</a>
			</li>

			<li>
				<a class="text-xl font-bold text-white"
				   href="/aly-training/">Ally Training</a>
			</li>

			<li>
				<a class="text-xl font-bold text-white line-through"
				   href="/strategic-plan/">Strategic Plan</a>
			</li>

			<li>
				<a class="text-xl font-bold text-white"
				   href="/research/">Research</a>
			</li>

			<li>
				<a class="text-xl font-bold text-white"
				   href="<?= get_post_type_archive_link( \TrevorWP\CPT\Research::POST_TYPE ) ?>">Research Briefs</a>
			</li>

			<li>
				<a class="text-xl font-bold text-white"
				   href="/meet-our-partners/">Meet Our Partners</a>
			</li>

			<li>
				<a class="text-xl font-bold text-white"
				   href="/team/">Team</a>
			</li>

			<li>
				<a class="text-xl font-bold text-white"
				   href="/careers/">Careers</a>
			</li>

			<li>
				<a class="text-xl font-bold text-white"
				   href="/contact-us/">Contact Us</a>
			</li>

			<li>
				<a class="text-xl font-bold text-white"
				   href="/financial-reports/">Financial Reports</a>
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
