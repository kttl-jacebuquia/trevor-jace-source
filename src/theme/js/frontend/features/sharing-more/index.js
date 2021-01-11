import tippy from 'tippy.js';

export default function sharingMore(button, content) {
	tippy(button, {
		content,
		interactive: true,
		placement: 'right',
	});
}
