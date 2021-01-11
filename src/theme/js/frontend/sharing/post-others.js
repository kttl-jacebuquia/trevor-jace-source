import $ from 'jquery';
import sharingMore from 'theme/js/frontend/features/sharing-more'

const hasNavigatorShare = !!navigator.share;

$(() => {
	const $btn = $('.post-share-others-btn');

	if (hasNavigatorShare) {
		$btn.on('click', () => navigator.share({
			url: $('head link[rel="canonical"]').attr('href') || window.location.href,
		}));
	} else {
		sharingMore($btn.get(0), $('.post-share-others-content').get(0));
	}
});
