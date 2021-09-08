export const pushFormData = (formName, success) => {
	window.dataLayer && window.dataLayer.push({
		'event': 'form',
		'eventType': `${formName} submit`,
		'formStatus': success ? 'success' : 'fail'
	});
}
