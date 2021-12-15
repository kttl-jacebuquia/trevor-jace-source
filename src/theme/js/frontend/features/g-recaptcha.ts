const recaptchaImageSelectIframeSrcPattern = /google\.com\/recaptcha\/enterprise\/bframe/gi;

const onMutation = (mutations: MutationRecord[]) => {
	mutations.forEach(({ addedNodes }: MutationRecord) => {
		Array.from(addedNodes).forEach((el) => {
			const element = el as HTMLElement;
			if (
				element instanceof window.HTMLIFrameElement &&
				recaptchaImageSelectIframeSrcPattern.test(element.src || '')
			) {
				element?.parentElement?.classList.add(
					'g-recaptcha-image-select-container'
				);
			}
		});
	});
};

const mutationObserver = new window.MutationObserver(onMutation);
mutationObserver.observe(document.body, { subtree: true, childList: true });
