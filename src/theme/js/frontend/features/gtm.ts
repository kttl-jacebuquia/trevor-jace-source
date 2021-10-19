export const pushFormData = (formName: string, success: boolean) => {
	if (window.dataLayer) {
		window.dataLayer.push({
			event: 'form',
			eventType: `${formName} submit`,
			formStatus: success ? 'success' : 'fail',
		});
	}
};
