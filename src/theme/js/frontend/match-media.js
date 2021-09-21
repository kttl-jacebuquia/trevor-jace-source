const carouselWith3CardsQ = window.matchMedia(`screen and (min-width: 415px) and (max-width: 1275px)`);
const onlyLargeQ = window.matchMedia(`screen and (min-width: 1024px)`);
const onlyXLargeQ = window.matchMedia(`screen and (min-width: 1440px)`);
const mobileAndTabletQ = window.matchMedia(`screen and (max-width: 1023px)`);
const tabletAndUpQ = window.matchMedia(`screen and (min-width: 768px)`);

// single breakpoints
// Usage: matchMedia.smallDesktopOnly( matches, noMatches );
export const singleBreakpoints = {
	mobileOnly: { max: 767 },
	tabletOnly: { min: 768, max: 1023 },
	smallDesktopOnly: { min: 1024, max: 1279 },
	mediumDesktopOnly: { min: 1280, max: 1439 },
	largeDesktopOnly: { min: 1440 },
};

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

// Single breakpoints queries
const buildSingleBreakpointMedia = ( singleBreakpointSettings ) => {
	const widths = [];

	if ( 'min' in singleBreakpointSettings ) {
		widths.push(`(min-width: ${singleBreakpointSettings.min}px)`);
	}

	if ( 'max' in singleBreakpointSettings ) {
		widths.push(`(max-width: ${singleBreakpointSettings.max}px)`);
	}

	const mediaQuery = `screen and ${widths.join(' and ')}`;
	return watcherFactory(window.matchMedia(mediaQuery));
}

export const mobileOnly = buildSingleBreakpointMedia(singleBreakpoints.mobileOnly);
export const tabletOnly = buildSingleBreakpointMedia(singleBreakpoints.tabletOnly);
export const smallDesktopOnly = buildSingleBreakpointMedia(singleBreakpoints.smallDesktopOnly);
export const mediumDesktopOnly = buildSingleBreakpointMedia(singleBreakpoints.mediumDesktopOnly);
export const largeDesktopOnly = buildSingleBreakpointMedia(singleBreakpoints.largeDesktopOnly);

