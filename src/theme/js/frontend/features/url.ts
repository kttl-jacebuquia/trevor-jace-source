export const getParams = () =>
	Object.fromEntries(new URLSearchParams(location.search));

export const replaceParams = (
	params: Record<string, string> = {},
	updateHistory = false
) => {
	const url = new URL(location.origin + location.pathname);
	const search = new URLSearchParams(location.search);

	Object.entries(params).forEach(([key, value]) => search.set(key, value));
	url.search = search.toString();

	const newURL = url.toString();

	if (updateHistory) {
		history.pushState(null, '', newURL);
	}

	return newURL;
};
