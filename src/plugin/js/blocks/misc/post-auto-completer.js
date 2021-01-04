export default (collectionName = 'Posts', _apiOptions = {}, context) => ({term: search}, response) => {
	const apiOptions = (typeof _apiOptions === 'function')
		? _apiOptions.apply(context) // call the lazy function
		: _apiOptions;

	// Update filters
	apiOptions.filter = (apiOptions.filter || {});
	if (typeof apiOptions.filter.nopaging === 'undefined') {
		apiOptions.filter.nopaging = true;
	}

	const col = new wp.api.collections[collectionName]();

	col.fetch({
		data: {search, ...apiOptions}
	}).then(results => {
		if ('date' in col.args) {
			return postProcessor(results, response);
		} else {
			return termProcessor(results, response);
		}
	}, () => response());
}

function postProcessor(results, response) {
	response(results.map(post => ({label: post.title.rendered || `#${post.id}`, value: post.id})));
}

function termProcessor(results, response) {
	response(results.map(term => ({label: term.name || `#${term.id}`, value: term.id})));
}
