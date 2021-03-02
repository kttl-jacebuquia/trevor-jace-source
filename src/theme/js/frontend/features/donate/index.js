import $ from 'jquery';

const _frequency = $('.frequency--choice label');
const _amount = $('.amount-choice label');

export function toggleFrequency($btn) {
	_frequency.removeClass('is-selected');
	$btn.toggleClass('is-selected');
}


export function toggleAmount($btn) {
	_amount.removeClass('is-selected');
	$btn.toggleClass('is-selected');
}
