import $ from 'jquery';

export default (_args = {}) => {
	const args = Object.assign(
		{
			containerSelector: '.tile-grid-container',
			siteContentSelector: '#site-content',
			paginatorSelector: '.ajax-pagination',
		},
		_args
	);

	$(args.paginatorSelector).each((idx, elem) => {
		const $elem = $(elem);

		$elem.find('a.next').on('click', (e) => {
			e.preventDefault();

			// get data attrs
			let options = {
				containerSelector:
					$elem.data('containerselector') || args.containerSelector,
				siteContentSelector:
					$elem.data('sitecontentselector') ||
					args.siteContentSelector,
			};

			const $container = $(options.siteContentSelector).find(
				options.containerSelector
			);
			const $nextLink = $(e.target);

			$nextLink.removeClass('loading').addClass('loading');

			$.get(
				$nextLink.attr('href'),
				{ ajax_pagination: 1 },
				(data) => {
					$nextLink.removeClass('loading');

					const $newSiteContent = $(data).filter(
						options.siteContentSelector
					);
					const $newCards = $newSiteContent
						.find(options.containerSelector)
						.children();

					$newCards.appendTo($container);

					const $newNextLink = $newSiteContent
						.find(args.paginatorSelector)
						.find('a.next');

					if ($newNextLink.length === 0) {
						// No more pages, hide
						$nextLink.hide();
					} else {
						// Replace new page link
						$nextLink.attr('href', $newNextLink.attr('href'));
					}

					// Focus on the first newly added card
					$newCards.get(0)?.focus();
				},
				'html'
			);
		});
	});
};
