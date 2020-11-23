// Vendors
import 'what-input';
// Auto-Complete Test
import $ from 'jquery';

import * as features from './features';
import * as vendors from './vendors';


window.trevorWP = {features, vendors};

features.tagBoxEllipsis($('.card-post'));


jQuery(function ($) {
	$('#input-search').autocomplete({
		source: function ({term}, response) {
			const url = new URL($('meta[name=ac-ajax-url]').attr("content"));
			url.searchParams.set('term', term);

			$.post(url.toString(), (resp) => {
				console.log(term, resp);
				response(((resp.correctlySpelled || resp.collations.length === 0) ? [term] : []).concat(resp.collations.map(({query}) => query)));
			});
		}
	});

	$('#input-search-2').autocomplete({
		classes: {
			"ui-autocomplete": "highlight-2"
		},
		source: function ({term}, response) {
			const url = new URL($('meta[name=ac2-ajax-url]').attr("content"));
			url.searchParams.set('term', term);

			$.post(url.toString(), (resp) => {
				response(resp.results);
			});
		},
		select: function (event, ui) {
			window.location.href = ui.item.url;
		}
	}).autocomplete("instance")._renderItem = function (ul, item) {
		return $("<li class='hl-test-item'>")
			.append("<div class='title'>" + item.title + "</div><div class='content'>" + item.content + "</div>")
			.appendTo(ul);
	};
});
