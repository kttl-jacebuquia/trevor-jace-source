const blockName = 'core/separator';

wp.domReady(() => {
	wp.blocks.unregisterBlockStyle(blockName, 'default');
	wp.blocks.unregisterBlockStyle(blockName, 'wide');
	wp.blocks.unregisterBlockStyle(blockName, 'dots');

	wp.blocks.registerBlockStyle(blockName, [
		{
			name: 'wave',
			label: 'Wave',
			isDefault: true,
		}
	]);
});
