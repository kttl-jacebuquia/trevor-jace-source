import $ from 'jquery';

export default function faqToggle($btn) {
  $btn.toggleClass('is-open');
  $btn.next().toggleClass('is-open');
}
