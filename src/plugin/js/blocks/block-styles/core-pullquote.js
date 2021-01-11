const blockName = 'core/pullquote';

wp.domReady(() => {
	wp.blocks.unregisterBlockStyle(blockName, 'default');
	wp.blocks.unregisterBlockStyle(blockName, 'solid-color');

	wp.blocks.registerBlockStyle(blockName, [
		{
			name: 'trevor-default',
			label: 'Default',
			isDefault: true,
		}
	]);
});
