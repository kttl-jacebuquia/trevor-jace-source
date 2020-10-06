import $ from 'jquery';

if (wp && wp.customize) {
	wp.customize.bind('ready', () => {
		$('#customize-controls').on('keyup', (e) => {
			const {target} = e;
			const $target = $(target);
			if ($target.is('.customize-control-nav_menu_item input.edit-menu-item-subtitle')) {
				console.log($target);
			}
		});
	});
}
