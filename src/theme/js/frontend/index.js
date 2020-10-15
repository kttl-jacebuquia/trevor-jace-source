// Vendors
import 'what-input';
// Auto-Complete Test
import $ from 'jquery';

const baseUrl = $('meta[name=ac-ajax-url]').attr("content");

jQuery(function ($) {
	$('#input-search').autocomplete({
		source: function ({term}, response) {
			const url = new URL(baseUrl);
			url.searchParams.set('term', term);

			$.post(url.toString(), (resp) => {
				console.log(term, resp);
				response(resp.collations.map(({query}) => query));
			});
		}
	});
});
