import $ from 'jquery';

export default new (class {
	/**
	 * Returns page title as a jQuery object.
	 *
	 * @private
	 */
	_insert$elem($elem) {
		$elem.insertAfter($('h1').first());
	}

	/**
	 * Creates a notice container.
	 *
	 * @param {string} elem_class - Element class.
	 * @param {string} context - Message to show.
	 * @returns {string} html
	 * @private
	 */
	_getContainer(elem_class, context) {
		return `<div class="notice ${elem_class} below-h1">
                    <p>${context}</p>
                </div>`;
	}

	/**
	 * Makes the notice dismissible.
	 *
	 * @param {jQuery} $elem - jQuery object.
	 * @private
	 */
	_makeDismissible($elem) {
		$elem.addClass('is-dismissible');
		let $button = $(`<button type="button" class="notice-dismiss"><span class="screen-reader-text"></span></button>`);
		let btnText = 'Dismiss';
		$button.find('.screen-reader-text').text(btnText);
		$elem.append($button);

		$button.on('click.wp-dismiss-notice', function (e) {
			e.preventDefault();
			$elem.fadeTo(100, 0, function () {
				$(this).slideUp(100, function () {
					$(this).remove();
				});
			});
		});
	}

	/**
	 * Create and add custom notice.
	 *
	 * @param {string} context
	 * @param {Object} [args]
	 * @param {string} args.class
	 * @param {bool} args.dismissible
	 * @param {bool} args.animateIn
	 * @param {bool} args.clear
	 * @param {bool} args.scrollTop
	 * @returns {jQuery}
	 */
	add(context, args = {}) {
		args = Object.assign({
			class: 'updated',
			dismissible: true,
			animateIn: true,
			clear: true,
			scrollTop: true
		}, args);

		// Clear previous
		if (args.clear) this.clear();

		const $elem = $(this._getContainer(args.class, context));

		if (args.dismissible) this._makeDismissible($elem);

		this._insert$elem($elem.hide());

		if (args.animateIn) $elem.fadeIn(1000);
		else $elem.show();

		if (args.scrollTop) $('body, html').animate({scrollTop: 0}, 600);

		return $elem;
	}

	updateContext($elem, context) {
		if ($.contains(document.documentElement, $elem[0])) {
			// If $elem in the dom then just update the context
			$elem.first('p').html(`<p>${context}</p>`);
		} else {
			// $elem is not in dom, then add the new one
			const cls = ($elem && $elem.hasClass('error'))
				? 'error'
				: 'updated';
			this.add(context, {class: cls});
		}
	}

	/**
	 * Add error notice.
	 *
	 * @param {string} context
	 * @param {Object} args
	 * @returns {jQuery}
	 */
	addError(context, args = {}) {
		return this.add(context, Object.assign({class: 'error'}, args));
	}

	/**
	 * Add update notice.
	 *
	 * @param {string} context
	 * @param {Object} args
	 * @returns {jQuery}
	 */
	addUpdate(context, args = {}) {
		return this.add(context, Object.assign({class: 'updated'}, args));
	}

	/**
	 * Clears all notice messages.
	 */
	clear() {
		$('.notice.error.below-h1,.notice.updated.below-h1').hide(400, function () {
			$(this).remove();
		});
	}
});
