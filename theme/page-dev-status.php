<?php get_header(); ?>

<main id="site-content" role="main">
	<div class="container mx-auto text-center text-white">
		<table class="w-full table-auto">
			<style>
				tr > td {
					border-bottom: 1px dotted white;
				}
			</style>
			<thead>
			<tr>
				<th>Name</th>
				<th>Status</th>
				<th>Notes</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<th colspan="3" class="text-2xl pt-10 border-b">Sprint 1</th>
			</tr>
			<tr>
				<td>
					<a class="text-xl font-bold text-white"
					   href="<?php echo esc_attr( home_url( \TrevorWP\CPT\RC\RC_Object::PERMALINK_BASE ) ); ?>">
						Resources Center</a>
				</td>
				<td>Hardcoded</td>
				<td>TBD: Gutenberg</td>
			</tr>
			<tr>
				<td>
					<a class="text-xl font-bold text-white"
					   href="<?php echo esc_attr( home_url( \TrevorWP\CPT\RC\RC_Object::PERMALINK_TREVORSPACE ) ); ?>">TrevorSpace</a>
				</td>
				<td>WIP</td>
				<td>
					<a href="https://github.com/kettle/trevor-web/issues/515">Github</a>
					<p>see: Sprint 7</p>
				</td>
			</tr>
			<tr>
				<td>
					<a class="text-xl font-bold text-white"
					   href="<?php echo esc_attr( home_url( \TrevorWP\CPT\RC\RC_Object::PERMALINK_GET_HELP ) ); ?>">Get
						Help</a>
				</td>
				<td>WIP</td>
				<td>
					<a href="https://github.com/kettle/trevor-web/issues/515">Github</a>
				</td>
			</tr>


			<tr>
				<th colspan="3" class="text-2xl pt-10 border-b">Sprint 2</th>
			</tr>
			<tr>
				<td>
					<a class="text-xl font-bold text-white"
					   href="<?php echo esc_attr( home_url( \TrevorWP\CPT\Get_Involved\Get_Involved_Object::PERMALINK_ADVOCACY ) ); ?>">Advocacy</a>
				</td>
				<td>Hardcoded</td>
				<td>
					<a href="https://github.com/kettle/trevor-web/issues/452">Github</a>
				</td>
			</tr>
			<tr>
				<td>
					<a class="text-xl font-bold text-white"
					   href="<?php echo esc_attr( home_url( \TrevorWP\CPT\Get_Involved\Get_Involved_Object::PERMALINK_ECT ) ); ?>">Ending
						Conversion Therapy</a>
				</td>
				<td>Hardcoded</td>
				<td>
					<a href="https://github.com/kettle/trevor-web/issues/452">Github</a>
				</td>
			</tr>
			<tr>
				<td>
					<a class="text-xl font-bold text-white"
					   href="<?php echo esc_attr( home_url( \TrevorWP\CPT\Get_Involved\Get_Involved_Object::PERMALINK_VOLUNTEER ) ); ?>">Volunteer</a>
				</td>
				<td>Hardcoded</td>
				<td>
					<a href="https://github.com/kettle/trevor-web/issues/452">Github</a>
				</td>
			</tr>
			<tr>
				<td>
					<a class="text-xl font-bold text-white"
					   href="<?php echo esc_attr( home_url( \TrevorWP\CPT\Get_Involved\Get_Involved_Object::PERMALINK_PARTNER_W_US ) ); ?>">Partner
						with Us</a>
				</td>
				<td>Hardcoded</td>
				<td>
					<a href="https://github.com/kettle/trevor-web/issues/452">Github</a>
				</td>
			</tr>
			<tr>
				<td>
					<a class="text-xl font-bold text-white"
					   href="<?php echo esc_attr( home_url( \TrevorWP\CPT\Get_Involved\Get_Involved_Object::PERMALINK_CORP_PARTNERSHIPS ) ); ?>">Corporate
						Partnerships</a>
				</td>
				<td>Hardcoded</td>
				<td>
					<a href="https://github.com/kettle/trevor-web/issues/452">Github</a>
				</td>
			</tr>
			<tr>
				<td>
					<a class="text-xl font-bold text-white"
					   href="<?php echo esc_attr( home_url( \TrevorWP\CPT\Get_Involved\Get_Involved_Object::PERMALINK_INSTITUTIONAL_GRANTS ) ); ?>">Institutional
						Grants</a>
				</td>
				<td>Hardcoded</td>
				<td>
					<a href="https://github.com/kettle/trevor-web/issues/452">Github</a>
				</td>
			</tr>

			<tr>
				<th colspan="3" class="text-2xl pt-10 border-b">Sprint 3</th>
			</tr>
			<tr>
				<td><a class="text-xl font-bold text-white"
					   href="<?php echo esc_attr( home_url( \TrevorWP\CPT\Donate\Donate_Object::PERMALINK_DONATE ) ); ?>">Donate</a>
				</td>
				<td>WIP</td>
				<td><a href="https://github.com/kettle/trevor-web/issues/451">Github</a></td>
			</tr>
			<tr>
				<td><a class="text-xl font-bold text-white"
					   href="<?php echo esc_attr( home_url( \TrevorWP\CPT\Donate\Donate_Object::PERMALINK_FUNDRAISE ) ); ?>">Fundraise</a>
				</td>
				<td>Hardcoded</td>
				<td><a href="https://github.com/kettle/trevor-web/issues/451">Github</a></td>
			</tr>
			<tr>
				<td><a class="text-xl font-bold text-white"
					   href="<?php echo esc_attr( home_url( \TrevorWP\CPT\Donate\Donate_Object::PERMALINK_PROD_PARTNERSHIPS ) ); ?>">Product
						Partnerships</a></td>
				<td>Hardcoded</td>
				<td><a href="https://github.com/kettle/trevor-web/issues/451">Github</a></td>
			</tr>
			<tr>
				<td><a class="text-xl font-bold text-white"
					   href="<?php echo esc_attr( home_url( \TrevorWP\CPT\Donate\Donate_Object::PERMALINK_PROD_PARTNERS ) ); ?>">Shop
						our Product Partners</a></td>
				<td>Hardcoded</td>
				<td><a href="https://github.com/kettle/trevor-web/issues/451">Github</a></td>
			</tr>

			<tr>
				<th colspan="3" class="text-2xl pt-10 border-b">Sprint 4</th>
			</tr>
			<tr>
				<td><a class="text-xl font-bold text-white"
					   href="/support/">Support</a></td>
				<td>New</td>
				<td>
					<a href="https://github.com/kettle/trevor-web/issues/559">Github</a>
				</td>
			</tr>
			<tr>
				<td><a class="text-xl font-bold text-white"
					   href="/trevor/">Org Landing Page</a></td>
				<td>New</td>
				<td>
					<a href="https://github.com/kettle/trevor-web/issues/560">Github</a>
				</td>
			</tr>
			<tr>
				<td><a class="text-xl font-bold text-white"
					   href="/press/">Press</a></td>
				<td>New</td>
				<td>
					<a href="https://github.com/kettle/trevor-web/issues/561">Github</a>
				</td>
			</tr>

			<tr>
				<th colspan="3" class="text-2xl pt-10 border-b">Sprint 5</th>
			</tr>
			<tr>
				<td><a class="text-xl font-bold text-white"
					   href="/public-education/">Public Education</a></td>
				<td>Ready (Gutenberg)</td>
				<td></td>
			</tr>
			<tr>
				<td><a class="text-xl font-bold text-white"
					   href="/ally-training/">Ally Training</a></td>
				<td>Ready (Gutenberg)</td>
				<td></td>
			</tr>
			<tr>
				<td><a class="text-xl font-bold text-white line-through"
					   href="/strategic-plan/">Strategic Plan</a></td>
				<td>Hardcoded</td>
				<td>Waiting Design Update</td>
			</tr>
			<tr>
				<td><a class="text-xl font-bold text-white"
					   href="/research/">Research</a></td>
				<td>Ready (Gutenberg)</td>
				<td>
					<a href="https://github.com/kettle/trevor-web/issues/406">Github</a>
				</td>
			</tr>
			<tr>
				<td><a class="text-xl font-bold text-white"
					   href="<?php echo get_post_type_archive_link( \TrevorWP\CPT\Research::POST_TYPE ); ?>">Research
						Briefs</a></td>
				<td>Ready (Gutenberg)</td>
				<td>
					<a href="https://github.com/kettle/trevor-web/issues/407">Github</a>
				</td>
			</tr>
			<tr>
				<td><a class="text-xl font-bold text-white"
					   href="/meet-our-partners/">Meet Our Partners</a></td>
				<td>Ready (Gutenberg)</td>
				<td>
					<a href="https://github.com/kettle/trevor-web/issues/378">Github</a>
				</td>
			</tr>
			<tr>
				<td><a class="text-xl font-bold text-white"
					   href="/team/">Team</a></td>
				<td>Ready (Gutenberg)</td>
				<td>
					<a href="https://github.com/kettle/trevor-web/issues/419">Github</a>
				</td>
			</tr>

			<tr>
				<td><a class="text-xl font-bold text-white"
					   href="/contact-us/">Contact Us</a></td>
				<td>Ready (Gutenberg)</td>
				<td></td>
			</tr>
			<tr>
				<td><a class="text-xl font-bold text-white"
					   href="/financial-reports/">Financial Reports</a></td>
				<td>Ready (Gutenberg)</td>
				<td>
					<a href="https://github.com/kettle/trevor-web/issues/455">Github</a>
				</td>
			</tr>
			<tr>
				<td><a class="text-xl font-bold text-white"
					   href="/terms-of-service/">Terms of Service</a></td>
				<td>Ready (Page Template)</td>
				<td>
					<a href="https://github.com/kettle/trevor-web/issues/503">Github</a>
				</td>
			</tr>
			<tr>
				<td><a class="text-xl font-bold text-white"
					   href="/privacy-policy/">Privacy Policy</a></td>
				<td>Ready (Page Template)</td>
				<td>
					<a href="https://github.com/kettle/trevor-web/issues/503">Github</a>
				</td>
			</tr>

			<tr>
				<th colspan="3" class="text-2xl pt-10 border-b">Sprint 6</th>
			</tr>
			<tr>
				<td><a class="text-xl font-bold text-white js-fundraiser-quiz"
					   href="#">Fundraiser Quiz</a></td>
				<td>Ready</td>
				<td><a href="https://github.com/kettle/trevor-web/issues/497">Github</a></td>
			</tr>
			<tr>
				<td>
					<a class="text-xl font-bold text-white"
					   href="<?php echo get_post_type_archive_link( \TrevorWP\CPT\Post::POST_TYPE ); ?>">Blog</a>
				</td>
				<td>Ready (WP Archive)</td>
				<td></td>
			</tr>
			<tr>
				<td>
					<a class="text-xl font-bold text-white"
					   href="/events/">Events</a>
				</td>
				<td>Ready (Gutenberg)</td>
				<td><a href="https://github.com/kettle/trevor-web/issues/481">Github</a></td>
			</tr>

			<tr>
				<th colspan="3" class="text-2xl pt-10 border-b">Sprint 7</th>
			</tr>
			<tr>
				<td><a class="text-xl font-bold text-white"
					   href="/careers/">Careers</a></td>
				<td>Blocked</td>
				<td>
					<ul>
						<li>Client to provide ADP credentials</li>
					</ul>
				</td>
			</tr>
			<tr>
				<td><a class="text-xl font-bold text-white"
					   href="/get-help/">Get Help</a></td>
				<td>WIP</td>
				<td>
					<a href="https://github.com/kettle/trevor-web/issues/515">Github</a>
				</td>
			</tr>
			<tr>
				<td><a class="text-xl font-bold text-white"
					   href="#">Breathing Exercise</a></td>
				<td>WIP</td>
				<td></td>
			</tr>

			<tr>
				<th colspan="3" class="text-2xl pt-10 border-b">*</th>
			</tr>
			<tr>
				<td><a class="text-xl font-bold text-white"
					   href="<?php echo TrevorWP\Theme\Customizer\Search::get_permalink(); ?>">Site-Wide Search</a></td>
				<td>Ready</td>
				<td>
					<ul>
						<li>Some categories are missing</li>
					</ul>
				</td>
			</tr>
			</tbody>
		</table>
	</div>
</main> <!-- #site-content -->

<?php get_footer(); ?>
