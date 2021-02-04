import $ from 'jquery';

class CustomSelect {
	static collapseActiveClass = 'show';
	static collapseButton = 'accordion-button';
	static collapseBody = 'accordion-collapse';
	static collapseContainer = 'js-accordion';

	constructor($content) {
		this.$content = $content;
		this.$label = $content.find('.label');
		this.$dropdownItems = $content.find('.dropdown li');
		this.$label.on('click', this.toggle);
		this.$dropdownItems.on('click', this.sort);
	}

	toggle = () => {
		this.$label.toggleClass('show');
	}

	sort = (e) => {
		const $el = e.currentTarget;
		const sortType = $el.getAttribute('data-sort');

		// toggle active class
		this.$dropdownItems.removeClass('active');
		$el.classList.add('active');

		//update label
		this.$label.find('> button').text($el.innerHTML);

		var result = $('.tile-grid-container .tile').sort(function (a, b) {
			var contentA = new Date( $(a).data('date') ).getTime();
			var contentB = new Date( $(b).data('date') ).getTime();

			if ( sortType == 'desc' ) {
				return (contentA < contentB) ? -1 : (contentA > contentB) ? 1 : 0;
			} else {
				return (contentA > contentB) ? -1 : (contentA < contentB) ? 1 : 0;
			}
		}).appendTo('.tile-grid-container');
	}

}


export default function customSelect($content) {
	const controller = new CustomSelect($content);
}
