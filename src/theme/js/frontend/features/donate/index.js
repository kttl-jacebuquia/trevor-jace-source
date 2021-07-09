import $ from 'jquery';

const _frequency = $('.frequency--choices label');
const _amount = $('.amount-choices label');
const _donateForm = $('#donate-form');
const _customAmount = $('.custom-amount', _donateForm);
const _displayAmount = $('.display-amount', _donateForm);
const _fixedAmount = $('.fixed-amount');
const _displayFormatAmount = $("input[data-type='currency']");

export function toggleFrequency() {
	_frequency.click(function(){
		_frequency.removeClass('is-selected');
		$(this).toggleClass('is-selected');
	});
	_frequency.on('keyup', function(e){
		if ( /space|enter/i.test(e.code) ) {
			e.preventDefault();
			_frequency.removeClass('is-selected');
			$(this).toggleClass('is-selected');
			return false;
		}
	});
}

export function toggleAmount() {
	_amount.click(function(){
		_amount.removeClass('is-selected');
		$(this).toggleClass('is-selected');
		_customAmount.val('');
		_displayAmount.val('');
	});

	_amount.on('keyup', function (e) {
		if ( /space|enter/i.test(e.code) ) {
			e.preventDefault();
			_amount.removeClass('is-selected');
			$(this).toggleClass('is-selected');
			_customAmount.val('');
			_displayAmount.val('');
			return false;
		}
	});
}

export function displayAmountAction() {
	_displayAmount.bind('keyup', function () {
		_amount.removeClass('is-selected');
		_fixedAmount.prop('checked', false);
	});

	_donateForm.submit( function(e){
		e.preventDefault();
		let _newCustomAmount = _customAmount.val();
		let _isRecurring = $('.donation-frequency:checked');
		_customAmount.prop('disabled', true);
		_displayAmount.prop('disabled', true);



		if( _newCustomAmount !== '' ) {
			$('.donation-frequency').prop('disabled', true);
			let _url = 'https://give.thetrevorproject.org/give/63307/#!/donation/checkout?amount=' + _newCustomAmount;

			if( _isRecurring.val() == 1 ) {
				_url += '&recurring=1';
			}

			_donateForm.attr( 'action', _url  );
		}

		e.currentTarget.submit();
	});
}

export function displayCurrency() {
	_displayFormatAmount.on({
		keyup: function() {
			formatCurrency($(this));
		},
		blur: function() {
			formatCurrency($(this), "blur");
		}
	});
}

function formatNumber(number) {
	// format number 1000000 to 1,234,567

	return number.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}


function formatCurrency(input, blur) {
	// appends $ to value, validates decimal side
	// and puts cursor back in right position.

	let currency = input.val();

	let cleanCurrency = currency.replace(/[$,]+/g,"");
	_customAmount.val( cleanCurrency );


	// get input value
	let input_value = input.val();

	// don't validate empty input
	if (input_value === "") { return; }

	// original length
	let original_length = input_value.length;

	// initial caret position
	let caret_position = input.prop("selectionStart");

	// check for decimal
	if (input_value.indexOf(".") >= 0) {

		// get position of first decimal
		// this prevents multiple decimals from
		// being entered
		let decimal_position = input_value.indexOf(".");

		// split number by decimal point
		let left_side_whole_number = input_value.substring(0, decimal_position);
		let right_side_decimals = input_value.substring(decimal_position);

		// add commas to left side of number
		left_side_whole_number = formatNumber(left_side_whole_number);

		// validate right side
		right_side_decimals = formatNumber(right_side_decimals);

		// On blur make sure 2 numbers after decimal
		if (blur === "blur") {
			right_side_decimals += "00";
		}

		// Limit decimal to only 2 digits
		right_side_decimals = right_side_decimals.substring(0, 2);

		// join number by .
		input_value = "$" + left_side_whole_number + "." + right_side_decimals;

	} else {
		// no decimal entered
		// add commas to number
		// remove all non-digits
		input_value = formatNumber(input_value);
		input_value = "$" + input_value;

		// final formatting
		if (blur === "blur") {
			input_value += ".00";
		}
	}

	// send updated string to input
	input.val(input_value);

	// put caret back in the right position
	let updated_length = input_value.length;
	caret_position = updated_length - original_length + caret_position;
	input[0].setSelectionRange(caret_position, caret_position);
}
