// Simple moves wp-seo-meta field to the bottom of the editor
const moveSeoMetaTab = () => {
	const editorContainer = document.querySelector('#editor');

	const observer = new MutationObserver(() => {
		const metaBoxesContainer = document.querySelector('.edit-post-layout__metaboxes');
		const wpSeoMetaBox = document.querySelector('#wpseo_meta');

		console.log({ metaBoxesContainer, wpSeoMetaBox });

		if ( metaBoxesContainer && wpSeoMetaBox ) {
			metaBoxesContainer.insertAdjacentElement('beforebegin', wpSeoMetaBox);
			observer.disconnect();
		}
	});

	observer.observe(editorContainer, { subtree: true, childList: true });
}

moveSeoMetaTab();
