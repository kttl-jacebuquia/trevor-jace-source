import whatInput from 'what-input';
import $ from 'jquery';
import Swiper from 'swiper';


export function carousel($element, $option) {

	let _el = '#' + $element;

	const swiper = new Swiper(_el, {
		// Optional parameters
		direction: 'horizontal',
		loop: true,
		// If we need pagination
		pagination: {
			el: '.swiper-pagination',
			clickable: true,
		}
	});

}



export function carouselNavigator($element, $option) {

	const swiper = new Swiper($element, {
		// Optional parameters
		direction: 'horizontal',
		loop: true,
		cssMode: true,
		// If we need pagination
		pagination: {
			el: '.swiper-pagination',
			clickable: true,
		}
	});

}

export function generateSwiperArrows(leftPaneSelector, rightPaneSelector, eBase) {
	const panesContainer = $(leftPaneSelector, eBase).parent();
	const iconTopOffset = 10;
	const iconLeftOffset = 37;
	let iconWrapper = undefined;
	const leftPane = eBase.querySelector(leftPaneSelector);
	const rightPane = eBase.querySelector(rightPaneSelector);
	leftPane.innerHTML = '';
	rightPane.innerHTML = '';

	/**
	 * set the direction if not defined
	 */
	if (typeof leftPane.dataset.direction === 'undefined') {
		leftPane.dataset.direction = 'left';
	}
	if (typeof rightPane.dataset.direction === 'undefined') {
		rightPane.dataset.direction = 'right';
	}

	/**
	 * mousemove on document is used to detect if the mouse
	 * is inside the panes because pane.onmousemove will not
	 * work properly as the icon is directly under the cursor.
	 */
	document.addEventListener('mousemove', e => {
		const leftPaneRect = eBase.querySelector(leftPaneSelector).getBoundingClientRect();
		const rightPaneRect = eBase.querySelector(rightPaneSelector).getBoundingClientRect();

		/**
		 * if the mouse is inside the left pane,
		 * generate and animate the swiper button.
		 */
		if (
			e.clientX >= leftPaneRect.left && e.clientX <= leftPaneRect.right &&
			e.clientY >= leftPaneRect.top && e.clientY <= leftPaneRect.bottom
		) {
			if (typeof iconWrapper === 'undefined') {
				generateSwiperButton(leftPaneSelector, e);
			}
			animateSwiperButton(leftPaneSelector, e);
		}
		/**
		 * if the mouse is inside the right pane,
		 * generate and animate the swiper button.
		 */
		else if (
			e.clientX >= rightPaneRect.left && e.clientX <= rightPaneRect.right &&
			e.clientY >= rightPaneRect.top && e.clientY <= rightPaneRect.bottom
		) {
			if (typeof iconWrapper === 'undefined') {
				generateSwiperButton(rightPaneSelector, e);
			}
			animateSwiperButton(rightPaneSelector, e);
		}
		/**
		 * if the mouse is neither in the left or right pane,
		 * reset the values.
		 */
		else {
			leftPane.innerHTML = '';
			rightPane.innerHTML = '';
			iconWrapper = undefined;
		}
	});

	/**
	 *
	 * @param {className} paneSelector
	 * @param {object} event
	 *
	 * This will generate the swiper button
	 * in the cursor position inside the pane.
	 */
	function generateSwiperButton(paneSelector, event) {
		const pane = document.querySelector(paneSelector);
		iconWrapper = document.createElement('div');
		iconWrapper.classList.add('swiper-button-wrapper');
		iconWrapper.classList.add('absolute');
		const icon = document.createElement('i');
		const direction = pane.dataset.direction;
		icon.classList.add(`trevor-ti-arrow-${direction}`);
		icon.setAttribute('aria-hidden', true);
		iconWrapper.appendChild(icon);
		pane.appendChild(iconWrapper);
		$('.swiper-button-wrapper', pane).offset({
			top: event.pageY - iconTopOffset,
			left: event.pageX - iconLeftOffset,
		});
	}

	/**
	 *
	 * @param {className} paneSelector
	 * @param {object} event
	 *
	 * This will animate the swiper button
	 * on mousemove event.
	 */
	function animateSwiperButton(paneSelector, event) {
		const pane = document.querySelector(paneSelector);
		if (whatInput.ask('intent') === 'mouse') {
			panesContainer.removeClass('is-mobile-breakpoint');
			$('.swiper-button-wrapper', pane).offset({
				top: event.pageY - iconTopOffset,
				left: event.pageX - iconLeftOffset,
			});
		} else {
			panesContainer.addClass('is-mobile-breakpoint');
		}
	}

	window.addEventListener('resize', e => {
		let desktop = window.matchMedia('(min-width: 1024px)');
		if (desktop.matches) {
			panesContainer.removeClass('is-mobile-breakpoint');
		} else {
			panesContainer.addClass('is-mobile-breakpoint');
		}
	});
}
