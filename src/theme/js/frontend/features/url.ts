export const getParams = () =>
	Object.fromEntries(new URLSearchParams(location.search));
