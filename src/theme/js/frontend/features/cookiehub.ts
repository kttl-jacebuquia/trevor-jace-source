const COOKIE_BANNER_CLASSNAME = 'ch2';
// Refer to _root.scss
const FLOATING_BUTTON_OFFSET_VARIABLE = '--floating-crisis-button-bottom';

const onBodyMutate = (
	mutations: MutationRecord[],
	observer: MutationObserver
) => {
	if (
		mutations.some(({ addedNodes }: MutationRecord) =>
			[...addedNodes].some((node: HTMLElement) =>
				node.classList?.contains(COOKIE_BANNER_CLASSNAME)
			)
		)
	) {
		observer.disconnect();
		handleCookieBanner();
	}
};

const checkCookieBannerVisibility = (cookieBannerDialog: HTMLElement) => {
	if (cookieBannerDialog.classList.contains('ch2-visible')) {
		document.body.style.setProperty(
			FLOATING_BUTTON_OFFSET_VARIABLE,
			cookieBannerDialog.offsetHeight + 'px'
		);
	} else {
		document.body.style.removeProperty(FLOATING_BUTTON_OFFSET_VARIABLE);
	}
};

const handleCookieBanner = () => {
	const cookieBannerDialog = document.querySelector(
		`.${COOKIE_BANNER_CLASSNAME}-dialog`
	);

	// Dialog visibility is triggered by the classname
	// So mutation observer is needed
	const mutationObserver = new window.MutationObserver(() =>
		checkCookieBannerVisibility(cookieBannerDialog as HTMLElement)
	);

	// Observe resize in order to pickup the right height
	// of the dialog
	const resizeObserver = new window.ResizeObserver(() => {
		checkCookieBannerVisibility(cookieBannerDialog as HTMLElement);
	});

	mutationObserver.observe(cookieBannerDialog as Node, { attributes: true });
	resizeObserver.observe(cookieBannerDialog as Element);
};

new window.MutationObserver(onBodyMutate).observe(document.body, {
	subtree: true,
	childList: true,
});
