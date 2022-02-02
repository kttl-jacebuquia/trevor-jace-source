const skipToMain = document.querySelector('#skip-to-main') as HTMLElement;

skipToMain.addEventListener('click', (e: MouseEvent) => {
	const targetSelector = skipToMain.getAttribute('href') as string;
	const target = document.querySelector(targetSelector) as HTMLElement;
	if (target) {
		// Focus to main, adding delay to ensure document has finished scrolling
		setTimeout(() => {
			target.focus();
		}, 300);
	}
});
