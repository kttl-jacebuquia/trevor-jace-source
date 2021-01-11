import './core-separator';
import './core-pullquote';
import './core-image';

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
