import $ from 'jquery';

export default (_args = {}) => {
	const args = Object.assign(
		{
			containerSelector: '.tile-grid-container',
			siteContentSelector: '#site-content',
			sorterSelector: '.ajax-sorter',
		},
		_args
	);

	$(args.sorterSelector).each((idx, elem) => {
		const $elem = $(elem);
		const sortLinks = $elem.find('a.sort-link');
		const dropdownButton = $elem.find('button.sort-select');

		sortLinks.each(function () {
			$(this).on('click', function (e) {
				e.preventDefault();

				const $linkParent = $(this).parent();

				if ($linkParent.hasClass('active')) {
					return;
				}

				$linkParent.addClass('active').siblings().removeClass('active');

				const sortValue = $(this)
					.text()
					.replace(/[\n\r]/g, '')
					.trim();

				dropdownButton.html(sortValue);

				// get data attrs.
				const options = {
					containerSelector:
						$elem.data('containerselector') ||
						args.containerSelector,
					siteContentSelector:
						$elem.data('sitecontentselector') ||
						args.siteContentSelector,
				};

				const $container = $(options.siteContentSelector).find(
					options.containerSelector
				);

				$.get(
					$(this).attr('href'),
					{},
					(data) => {
						const $newSiteContent = $(data).filter(
							options.siteContentSelector
						);
						const sortedContents = $newSiteContent
							.find(options.containerSelector)
							.children();

						// Rset Contents.
						$container.html(sortedContents);

						const $ajaxPagination = $('.ajax-pagination');
						const $numberedPagination = $('.number-pagination');

						// Reset pagination links.
						if ($ajaxPagination.length) {
							const $paginationLink =
								$ajaxPagination.find('a.next');
							const $resetLink = $newSiteContent
								.find('.ajax-pagination')
								.find('a.next');

							$paginationLink.attr(
								'href',
								$resetLink.attr('href')
							);
						} else if ($numberedPagination.length) {
							const $pageNumberLink = $newSiteContent
								.find('.number-pagination')
								.find('a.page-numbers')
								.last();

							if ($pageNumberLink.length) {
								const matches = $pageNumberLink
									.attr('href')
									.match(/order=([^&]*)/);
								const queryParam = matches[0];
								const $pageNumbers =
									$numberedPagination.find('a.page-numbers');

								$pageNumbers.each(function () {
									const withQueryParam = $(this)
										.attr('href')
										.match(/order=([^&]*)/);
									const hrefValue = withQueryParam
										? $(this)
												.attr('href')
												.replace(
													/order=([^&]*)/g,
													queryParam
												)
										: $(this).attr('href') +
										  '?' +
										  queryParam;

									$(this).attr('href', hrefValue);
								});
							}
						}
					},
					'html'
				);
			});
		});
	});
};
