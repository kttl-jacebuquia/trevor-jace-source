const onlyMobileQ = window.matchMedia(`screen and (max-width: 768px)`);
const onlyMediumQ = window.matchMedia(`screen and (min-width: 768px) and (max-width: 1440px)`);
const onlyLargeQ = window.matchMedia(`screen and (min-width: 1440px)`);

const watcherFactory = (q) => (matches, noMatches) => {
	const test = (e) => q.matches ? (matches && matches(e)) : (noMatches && noMatches(e));
	q.addListener(test);
	test();

	return () => q.removeListener(test);
}

export const onlyMobile = watcherFactory(onlyMobileQ);

export const onlyMedium = watcherFactory(onlyMediumQ);

export const onlyLarge = watcherFactory(onlyLargeQ);
