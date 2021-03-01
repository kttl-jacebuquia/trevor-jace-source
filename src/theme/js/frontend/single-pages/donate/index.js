import $ from 'jquery';
import * as features from "theme/js/frontend/features";

export default () => {
	const $frequency = $('.frequency--choice label');
	const $amount = $('.amount-choice label');

	$frequency.on('click', function (e) {
		e.preventDefault();
		features.toggleFrequency($(this));
	});

	$amount.on('click', function (e) {
		e.preventDefault();
		features.toggleAmount($(this));
	});
}
