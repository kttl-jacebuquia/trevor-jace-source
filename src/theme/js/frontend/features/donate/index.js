import $ from 'jquery';
const _frequency = $('.frequency--choice label');
const _amount = $('.amount-choice label');

export function toggleFrequency($btn) {
  _frequency.removeClass('selected');
  $btn.toggleClass('selected');
}


export function toggleAmount($btn) {
  _amount.removeClass('selected');
  $btn.toggleClass('selected');
}
