(function ($) {
	$.fn.ForceNumericOnly = function () {
		return this.each(function () {
			$(this).keydown(function (e) {
				const key = e.charCode || e.keyCode || 0;
				let isShift;
				if (window.event) {
					isShift = !!window.event.shiftKey;
				} else {
					isShift = !!e.shiftKey;
				}
				return (
					!isShift &&
					(key === 8 ||
						key === 9 ||
						key === 46 ||
						key === 110 ||
						key === 190 ||
						(key >= 35 && key <= 40) ||
						(key >= 48 && key <= 57) ||
						(key >= 96 && key <= 105))
				);
			});
		});
	};
	// eslint-disable-next-line no-undef
})(jQuery);
(function () {
	const numberCallback = function (field) {
		field.$el.find('input').ForceNumericOnly();
	};

	// eslint-disable-next-line no-undef
	if (typeof acf !== 'undefined') {
		// eslint-disable-next-line no-undef
		acf.addAction('new_field/type=number', numberCallback);
	}
	// eslint-disable-next-line no-undef
})(jQuery);
