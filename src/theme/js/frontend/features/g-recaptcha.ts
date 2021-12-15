const recaptchaImageSelectIframeSrcPattern =
	/google\.com\/recaptcha\/enterprise\/bframe/gi;

const onMutation = (mutations: MutationRecord[]) => {
	mutations.forEach(({ addedNodes }: MutationRecord) => {
		Array.from(addedNodes).forEach((el) => {
			const element = el as HTMLElement;

			if (!(element instanceof window.HTMLElement)) {
				return;
			}

			const possibleRecaptchaIframe =
				element instanceof window.HTMLIFrameElement
					? element
					: element.querySelector('iframe');
			if (
				possibleRecaptchaIframe instanceof window.HTMLIFrameElement &&
				recaptchaImageSelectIframeSrcPattern.test(
					possibleRecaptchaIframe.src || ''
				)
			) {
				possibleRecaptchaIframe.classList.add(
					'g-recaptcha-image-select-container'
				);
			}
		});
	});
};

const mutationObserver = new window.MutationObserver(onMutation);
mutationObserver.observe(document.body, { subtree: true, childList: true });
