import $ from 'jquery';

export default (args = {}) => {
	const options = Object.assign({
		containerSelector: '.tile-grid-container',
		siteContentSelector: '#site-content',
		paginatorSelector: '.ajax-pagination',
	}, args);
	const $container = $(options.siteContentSelector).find(options.containerSelector);
	const $ajaxPagination = $(options.paginatorSelector);

	$ajaxPagination.find('a.next').on('click', (e) => {
		e.preventDefault();

		const $nextLink = $(e.target);

		$nextLink.removeClass('loading').addClass('loading');

		$.get($nextLink.attr('href'), {ajax_pagination: 1}, data => {
			$nextLink.removeClass('loading');

			const $newSiteContent = $(data).filter(options.siteContentSelector);

			$newSiteContent.find(options.containerSelector).children().appendTo($container);

			const $newNextLink = $newSiteContent.find(options.paginatorSelector).find('a.next');

			if ($newNextLink.length === 0) {
				// No more pages, hide
				$nextLink.hide();
			} else {
				// Replace new page link
				$nextLink.attr('href', $newNextLink.attr('href'));
			}
		}, 'html');
	});
}
