import $ from 'jquery';

export default function cardToggle($btn) {
	const _parent = $btn.parent().parent();
	const slideDuration = 50;


	_parent.toggleClass('is-open');
	$('.tile-desc, .tile-cta-wrap', _parent).slideToggle( slideDuration );
}
