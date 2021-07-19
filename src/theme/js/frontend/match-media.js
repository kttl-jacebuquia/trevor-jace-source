const carouselWith3CardsQ = window.matchMedia(`screen and (min-width: 415px) and (max-width: 1275px)`);
const onlyLargeQ = window.matchMedia(`screen and (min-width: 1024px)`);
const onlyXLargeQ = window.matchMedia(`screen and (min-width: 1440px)`);
const mobileAndTabletQ = window.matchMedia(`screen and (max-width: 1279px)`);
const tabletAndUpQ = window.matchMedia(`screen and (min-width: 768px)`);

const watcherFactory = (q) => (matches, noMatches) => {
	const test = (e) => q.matches ? (matches && matches(e)) : (noMatches && noMatches(e));
	q.addListener(test);
	test();

	return () => q.removeListener(test);
}


export const carouselWith3Cards = watcherFactory(carouselWith3CardsQ);
export const mobileAndTablet = watcherFactory(mobileAndTabletQ);
export const onlyLarge = watcherFactory(onlyLargeQ);
export const onlyXLarge = watcherFactory(onlyXLargeQ);
export const tabletAndUp = watcherFactory(tabletAndUpQ);
