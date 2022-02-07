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
				possibleRecaptchaIframe.parentElement?.classList.add(
					'g-recaptcha-image-select-container-parent'
				);
				onRecaptchaRendered();
			}
		});
	});
};

// Runs after we determine that a recaptcha validation has been added into the page
const onRecaptchaRendered = () => {
	// Recaptcha root element is a classless child of the body
	const bodyChildren: Element[] = Array.from(document.body.children).filter(
		(childElement: Element) =>
			childElement instanceof window.HTMLDivElement &&
			!childElement.className
	);

	bodyChildren.forEach((possibleRecaptchaRoot) => {
		if (
			possibleRecaptchaRoot.querySelector(
				'.g-recaptcha-image-select-container'
			)
		) {
			possibleRecaptchaRoot.classList.add(
				'g-recaptcha-image-select-root'
			);
		}
	});
};

const mutationObserver = new window.MutationObserver(onMutation);
mutationObserver.observe(document.body, { subtree: true, childList: true });
