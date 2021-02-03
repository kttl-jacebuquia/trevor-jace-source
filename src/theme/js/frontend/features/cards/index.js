import $ from 'jquery';

export default function cardToggle($btn) {
	console.log('Parent', $btn.parent().parent());
	$btn.parent().parent().toggleClass('is-open');
}
