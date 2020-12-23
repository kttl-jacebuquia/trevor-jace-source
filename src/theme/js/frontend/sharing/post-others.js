import $ from 'jquery';

const hasNavigatorShare = !!navigator.share;

$(() => {
	$('.post-share-others-btn').on('click', () => {
		if (hasNavigatorShare) {
			navigator.share({
				url: $('head link[rel="canonical"]').attr('href') || window.location.href,
			});
		} else {
			// TODO: Single post share: others
			alert('Not implemented.');
		}
	});
});
