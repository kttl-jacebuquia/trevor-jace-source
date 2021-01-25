const carouselWith3CardsQ = window.matchMedia(`screen and (min-width: 415px) and (max-width: 1275px)`);
const onlyLargeQ = window.matchMedia(`screen and (min-width: 1024px)`);

const watcherFactory = (q) => (matches, noMatches) => {
	const test = (e) => q.matches ? (matches && matches(e)) : (noMatches && noMatches(e));
	q.addListener(test);
	test();

	return () => q.removeListener(test);
}


export const carouselWith3Cards = watcherFactory(carouselWith3CardsQ);
export const onlyLarge = watcherFactory(onlyLargeQ);
