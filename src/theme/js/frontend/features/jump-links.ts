import $ from 'jquery';

// Manually scroll smoothly if browser doesn't support it
if (getComputedStyle(document.body).scrollBehavior !== 'smooth') {
	const $scrollableRoot = $('html,body');
	const $jumpLinks = $('a[href*="#"]');
	const [url] = window.location.href.split('#');

	$jumpLinks.on('click', (e) => {
		e.preventDefault();
		const hrefSegments = e.currentTarget.href.split('#');

		if (
			!hrefSegments[1] ||
			(hrefSegments[0] &&
				url.replace(/\/$/, '') !== hrefSegments[0].replace(/\/$/, ''))
		) {
			return;
		}

		const sectionSelector: string = `#${hrefSegments[1]}`;
		const $targetSection = $(sectionSelector);

		if ($targetSection.length) {
			// Get section's scroll amount
			const targetScroll =
				$targetSection.offset()?.top || window.pageYOffset;

			// Compute for scroll duration
			// Max 3000ms scroll duration
			const scrollDuration = Math.min(
				(150 * Math.abs(targetScroll - window.pageYOffset)) / 300,
				2000
			);

			// Animate scroll
			$scrollableRoot.animate(
				{ scrollTop: targetScroll },
				scrollDuration,
				'swing'
			);

			// Add history entry
			window.history.pushState(null, '', sectionSelector);
		}
	});
}
