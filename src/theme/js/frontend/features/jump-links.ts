import $ from 'jquery';

// Manually scroll smoothly if browser doesn't support it
if (getComputedStyle(document.body).scrollBehavior !== 'smooth') {
	const $scrollableRoot = $('html,body');
	const $jumpLinks = $('a[href^="#"]');

	$jumpLinks.on('click', e => {
		e.preventDefault();
		const sectionSelector = e.currentTarget.getAttribute('href');
		const $targetSection = $(sectionSelector);

		if ($targetSection.length) {
			// Get section's scroll amount
			const targetScroll = $targetSection.offset()?.top;
			$scrollableRoot.animate({ scrollTop: targetScroll });
		}
	});
}
