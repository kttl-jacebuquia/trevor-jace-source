const onlyMobileQ = window.matchMedia(`screen and (max-width: 768px)`);

export const onlyMobile = (matches, noMatches) => {
	const test = (e) => onlyMobileQ.matches ? (matches && matches(e)) : (noMatches && noMatches(e));
	onlyMobileQ.addListener(test);
	test();
}
