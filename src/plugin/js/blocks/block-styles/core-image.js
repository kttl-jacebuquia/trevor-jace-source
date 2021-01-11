const blockName = 'core/image';

wp.domReady(() => {
	wp.blocks.unregisterBlockStyle(blockName, 'default');
	wp.blocks.unregisterBlockStyle(blockName, 'rounded');

	wp.blocks.registerBlockStyle(blockName, [
		{
			name: 'trevor-default',
			label: 'Default',
			isDefault: true,
		}
	]);

	wp.blocks.registerBlockStyle(blockName, [
		{
			name: 'full',
			label: 'Full Width',
			isDefault: true,
		}
	]);
});
