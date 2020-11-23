<?php get_header(); ?>

<main id="site-content" role="main">

	<div class="container mx-auto">
		<div class="my-12 w-1/2 mx-auto">
			<input type="text" placeholder="Auto Complete Test" class="border p-2 w-full" id="input-search">
		</div>
	</div>

	<hr>

	<div class="container mx-auto">
		<div class="my-12 w-1/2 mx-auto">
			<input type="text" placeholder="Highlight Test" class="border p-2 w-full" id="input-search-2">
		</div>
	</div>
<!---->
<!--	<div class="my-12">-->
<!--		<input type="text" id="ac-search-input"/>-->
<!--	</div>-->

<!--	<script>-->
<!--		var client = algoliasearch('IET1AO35QO', '5068babd9e15c66f4d1ef17572958ce7')-->
<!--		var index = client.initIndex('wp_posts_post_query_suggestions');-->
<!--		jQuery('#ac-search-input').autocomplete({hint: false}, [-->
<!--			{-->
<!--				source: jQuery.fn.autocomplete.sources.hits(index, {hitsPerPage: 5}),-->
<!--				displayKey: 'query',-->
<!--				templates: {-->
<!--					suggestion: function (suggestion) {-->
<!--						return suggestion._highlightResult.query.value;-->
<!--					}-->
<!--				}-->
<!--			}-->
<!--		]).on('autocomplete:selected', function (event, suggestion, dataset, context) {-->
<!--			console.log(event, suggestion, dataset, context);-->
<!--		});-->
<!--	</script>-->

	<!--	<div class="my-12">-->
	<!--		--><? //= get_search_form() ?>
	<!--	</div>-->
</main> <!-- #site-content -->

<?php get_footer(); ?>
