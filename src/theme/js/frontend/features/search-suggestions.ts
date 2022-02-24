import $ from 'jquery';
const $inputSearchField = $('#rc-search-main');

/**
 * Search bar autocomplete function
 */
(() => {
	if ($inputSearchField.length) {
		const terms =
			$("input[name='rc-search--keys']")
				.val()
				?.trim()
				.split(',')
				.slice(0, -1) || [];
		const tags =
			$("input[name='rc-search--tags']")
				.val()
				?.trim()
				.split(',')
				.slice(0, -1) || [];
		const searchCancelIcon = $('.icon-search-cancel');
		const $suggestionsContainer = $('#input-suggestions');
		const maxInputSize = 35;
		const form = $('.search-form');

		if (!$inputSearchField.val()) {
			$inputSearchField.attr(
				'size',
				$inputSearchField.attr('placeholder')?.length
			);
		}

		if ($inputSearchField.val()) {
			$inputSearchField.parent().addClass('input-has-value');
			$inputSearchField.attr('size', $inputSearchField.val().length);
		}

		$inputSearchField
			.autocomplete({
				source(request, response) {
					const dataSource = request.term ? tags : terms;
					const results = $.ui.autocomplete.filter(
						dataSource,
						request.term
					);
					response(results);
				},
				minLength: 0,
				appendTo: $suggestionsContainer,
				focus: () => {
					$('.ui-helper-hidden-accessible').hide();
				},
				open() {
					/**
					 * The results will automatically show without a header
					 * when the user starts to type anything on the search bar.
					 */
					if ($(this).val() == '') {
						$('ul.ui-autocomplete')
							.prepend(
								'<li class="list-header"><h3>Popular Searches</h3></li>'
							)
							.attr('aria-hidden', 'true')
							.removeAttr('tabindex');
					}
				},
				select(event, ui) {
					$(this).addClass('has-value');
					$(this).parent().addClass('input-has-value');
					$inputSearchField.val(ui.item.value.trim());
					$inputSearchField.attr('size', ui.item.value.trim().length);
					moveCursorToEnd($(this)[0]);
				},
			})
			/**
			 * Immediately open the jQuery autocomplete
			 * when the user focuses the search bar
			 * even before they start typing anyting.
			 */
			.focus(function () {
				$(this).autocomplete('search', $(this).val());
			})
			.on('input', function (event) {
				const inputText = $(this).val().trim();
				if (!inputText) {
					$(this).parent().removeClass('input-has-value');
					$(this).attr('size', $(this).attr('placeholder').length);
				} else {
					$(this).parent().addClass('input-has-value');
					const size =
						$(this).val().length > 32
							? 32
							: $(this).val().length + 2;
					$(this).attr('size', size);
				}
			})
			.on('keypress', function (event) {
				if (event.keyCode === 13) {
					form.submit();
					return false;
				}
				if ($(this).html().length >= maxInputSize) {
					event.preventDefault();
					return false;
				}
			})
			.data('ui-autocomplete')._renderItem = function (ul, item) {
			return $('<li>')
				.append(`<a href="?s=${item.value}">${item.label}</a>`)
				.appendTo(ul);
		};

		searchCancelIcon.on('click', function (e) {
			$inputSearchField.val('');
			$inputSearchField.parent().removeClass('input-has-value');
			$inputSearchField.attr(
				'size',
				$inputSearchField.attr('placeholder').length
			);
			$inputSearchField.focus();
		});

		function moveCursorToEnd(el) {
			setTimeout(() => {
				if (el.innerText && document.createRange) {
					const selection = document.getSelection();
					const range = document.createRange();

					range.setStart(el.childNodes[0], el.innerText.length);
					range.collapse(true);
					selection.removeAllRanges();
					selection.addRange(range);
				}
			}, 100);
		}
	}
})();
