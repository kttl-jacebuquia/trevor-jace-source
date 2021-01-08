wp.domReady(() => {
	wp.blocks.registerBlockStyle('core/separator', [
		{
			name: 'wave',
			label: 'Wave',
			isDefault: true,
		}
	]);

	wp.blocks.unregisterBlockStyle('core/separator', 'default');
	wp.blocks.unregisterBlockStyle('core/separator', 'wide');
	wp.blocks.unregisterBlockStyle('core/separator', 'dots');
});

/*
 * List all block styles
 * https://wordpress.stackexchange.com/a/352560
 */
wp.domReady(() => {
	wp.blocks.getBlockTypes().forEach((block) => {
		if (_.isArray(block['styles'])) {
			console.log(block.name, _.pluck(block['styles'], 'name'));
		}
	});
});
