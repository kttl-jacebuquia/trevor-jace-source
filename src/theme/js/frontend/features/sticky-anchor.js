import $ from 'jquery';

export default () => {
	$(() => {
		/**
		 * Crisis Button docking
		 * @fixme: window.onload binding
		 */
		const $body = $('body');
		const stickyAnchors = $('.sticky-cta-anchor');
		const crisisButton = $('.floating-crisis-btn-wrap');
		const distanceFromElement = 40;
		const btnVerticalPadding = 24;
		let intersectedElement = undefined;
		let intersectingY = undefined;
		let leavingY = undefined;

		let options = {
			root: null,
			rootMargin: '0px',
			threshold: 0
		}

		let callback = (entries, observer) => {
			entries.forEach(entry => {
				if (entry.isIntersecting) {
					intersectingY = entry.boundingClientRect.y;
					if (typeof intersectedElement === 'undefined') {
						intersectedElement = entry.target;
					}

					if (entry.target === intersectedElement) {
						const targetRect = intersectedElement.getBoundingClientRect();
						const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
						$body.addClass('scroll-near-bottom');
						crisisButton.offset({
							top: (targetRect.top + scrollTop) - (distanceFromElement + btnVerticalPadding),
						});
					}
				} else {
					leavingY = entry.boundingClientRect.y;
					/**
					* Reset the crisis button if the user is scrolling up
					* AND leaves the intersected element.
					*/
					if (leavingY > intersectingY && entry.target === intersectedElement) {
						$body.removeClass('scroll-near-bottom');
						crisisButton.removeAttr('style');
					}
				}
			});
		}

		let observer = new IntersectionObserver(callback, options);
		/**
		* Intersection Observer runs asynchronously
		* so you can just add an item in the array (i.e., [element1, elment2, ...]).
		* The crisis button will dock on the first element it intersects based on the DOM structure.
		*/
		if (stickyAnchors.length) {
			$.each(stickyAnchors, (index, element) => {
				if (element) observer.observe(element);
			});
		}
	});
}
