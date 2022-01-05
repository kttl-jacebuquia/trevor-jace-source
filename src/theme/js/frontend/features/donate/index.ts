import $ from 'jquery';
import Component from '../../Component';
import { pushFormData } from '../gtm';

const _donateTargetURL =
	'https://give.thetrevorproject.org/give/63307#!/donation/checkout';

const MAX_DIGITS = 10;

export default class DonationForm extends Component {
	// Defines the element selector which will initialize this component
	static selector = '.donation-form';

	constructor(_donateForm) {
		super(_donateForm[0]);

		this._donateForm = _donateForm;
		this.$element = $(_donateForm);
		this._frequency = this.$element.find('.frequency--choices button');
		this._amount = this.$element.find('.amount-choices button');
		this._donateForm = this.$element.find('[id^="donate-form');
		this._customAmount = this.$element.find('.custom-amount', _donateForm);
		this._displayAmount = this.$element.find(
			'.display-amount',
			_donateForm
		);
		this._fixedAmount = this.$element.find('.fixed-amount');
		this._displayFormatAmount = this.$element.find(
			"input[data-type='currency']"
		);
		this._error = this.$element.find('.donation-form__error');
		this.formGTMName = 'donation form';
	}

	// Will be called upon component instantiation
	afterInit() {
		this.toggleFrequency();
		this.toggleAmount();
		this.displayAmountAction();
		this.displayCurrency();
	}

	onFrequencyToggle(freqElement) {
		const $freq = $(freqElement);
		const $radio = $('#' + $freq.attr('for'));
		this._frequency.removeClass('is-selected');
		$freq.toggleClass('is-selected');
		$radio[0].checked = true;
	}

	toggleFrequency() {
		this._frequency.on('click', ({ currentTarget }) => {
			this.onFrequencyToggle(currentTarget);
		});
	}

	onAmountToggle(amountElement) {
		const $amount = $(amountElement);
		const $radio = $('#' + $amount.attr('for'));
		this._amount.removeClass('is-selected');
		$amount.toggleClass('is-selected');
		this._customAmount.val('');
		this._displayAmount.val('');
		$radio[0].checked = true;
	}

	toggleAmount() {
		this._amount.on('click', ({ currentTarget }) => {
			this.onAmountToggle(currentTarget);
		});
	}

	displayAmountAction() {
		this._displayAmount.on('input', () => {
			this._amount.removeClass('is-selected');
			this._fixedAmount.prop('checked', false);
		});

		this._donateForm.on('submit', (e) => {
			e.preventDefault();

			// Get amount from custom amout or preset amount
			const amount =
				Number(
					this._customAmount.val() || this._donateForm[0].amount.value
				) || 0;
			const hasError = !Boolean(amount);
			const _isRecurring = this._donateForm.find(
				'.donation-frequency:checked'
			);

			// Toggle error based on whether an amount is provided
			this._error.toggleClass('hidden', !hasError);

			// Don't send if there's an error
			if (hasError) {
				pushFormData(this.formGTMName, false);

				return false;
			}

			const paramsObject = { amount };

			if (_isRecurring.val() == 1) {
				paramsObject.recurring = 1;
			}

			const paramsString = Object.entries(paramsObject)
				.reduce((all, entry) => all.concat(entry.join('=')), [])
				.join('&');
			const url = [_donateTargetURL, paramsString].join('?');

			pushFormData(this.formGTMName, true);

			// Redirect to Trevor's Classy form
			window.open(url, '_blank');
		});
	}

	displayCurrency() {
		this._displayFormatAmount.on({
			input: ({ currentTarget }) => {
				this.formatCurrency($(currentTarget));
			},
			blur: ({ currentTarget }) => {
				this.formatCurrency($(currentTarget), 'blur');
			},
		});
	}

	formatNumber(number: string) {
		// Limit number to 10 digits
		const limitedNumber = number
			.replace(/\D/g, '')
			.substring(0, MAX_DIGITS);

		// format number 1000000 to 1,234,567
		return limitedNumber
			.replace(/\D/g, '')
			.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
	}

	formatCurrency(input, blur) {
		// appends $ to value, validates decimal side
		// and puts cursor back in right position.
		let currency = input.val();

		let cleanCurrency = currency.replace(/[$,]+/g, '');
		this._customAmount.val(cleanCurrency);

		// get input value
		let input_value = input.val();

		// don't validate empty input
		if (input_value === '') {
			return;
		}

		// original length
		let original_length = input_value.length;

		// initial caret position
		let caret_position = input.prop('selectionStart');

		// check for decimal
		if (input_value.indexOf('.') >= 0) {
			// get position of first decimal
			// this prevents multiple decimals from
			// being entered
			let decimal_position = input_value.indexOf('.');

			// split number by decimal point
			let left_side_whole_number = input_value.substring(
				0,
				decimal_position
			);
			let right_side_decimals = input_value.substring(decimal_position);

			// add commas to left side of number
			left_side_whole_number = this.formatNumber(left_side_whole_number);

			// validate right side
			right_side_decimals = this.formatNumber(right_side_decimals);

			// On blur make sure 2 numbers after decimal
			if (blur === 'blur') {
				right_side_decimals += '00';
			}

			// Limit decimal to only 2 digits
			right_side_decimals = right_side_decimals.substring(0, 2);

			// join number by .
			input_value =
				'$' + left_side_whole_number + '.' + right_side_decimals;
		} else {
			// no decimal entered
			// add commas to number
			// remove all non-digits
			input_value = this.formatNumber(input_value);
			input_value = '$' + input_value;

			// final formatting
			if (blur === 'blur') {
				input_value += '.00';
			}
		}

		// send updated string to input
		input.val(input_value);

		if (blur !== 'blur') {
			// put caret back in the right position
			let updated_length = input_value.length;
			caret_position = updated_length - original_length + caret_position;
			input[0].setSelectionRange(caret_position, caret_position);
		}
	}
}

DonationForm.init();
