const ENDPOINT = '/wp-admin/admin-ajax.php?action=phone2action';

const emailRegex = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/i;

const validationMessages = {
	required: '* This is a required field.',
	phone: 'Please use a valid phone number (0,9,-)',
	number: 'This field must be number',
	email: 'Please use a valid email'
};

// Just a simple validation helper, nothing fancy
// Returns a message if failed, none if success
const validateField = ( value, rules ) => {
	const validationRules = [...rules]; // Prevent mutation

	while ( validationRules.length ) {
		switch ( validationRules.shift() ) {
			case 'required':
				if ( !value ) { return validationMessages.required; }
				break;
			case 'phone':
				if ( /[^0-9\-\(\)\s]/.test(value) ) { return validationMessages.phone; }
				break;
			case 'number':
				if ( /[^0-9]/.test(value) ) { return validationMessages.number; }
				break;
			case 'email':
				if ( !emailRegex.test(value) ) { return validationMessages.email; }
				break;
		}
	}
}

export default class CampaignForm {
	static selector = '.join-the-campaign-form';

	formSelector = '.join-the-campaign-form__form';
	successSelector = '.join-the-campaign-form__success';
	validationRules = {
		fullname: [ 'required' ],
		email: [ 'required', 'email' ],
		phone: [ 'phone' ], // dynamically adds required if sms_notif is on
		zipcode: [ 'required', 'number' ],
	};

	constructor(element) {
		this.element = element;
	}

	init() {
		this.initializeForm();
	}

	initializeForm() {
		this.form = this.element.querySelector(this.formSelector);
		this.formSuccess = this.element.querySelector(this.successSelector);
		this.fields = [...this.form.elements].filter(element => element.name);

		if ( this.form ) {
			this.form.addEventListener('change', this.onFormChange.bind(this));
			this.form.addEventListener('submit', this.onFormSubmit.bind(this));
			this.checkSMSNotif();
		}
	}

	onFormChange({ target }) {
		// Validate changed field
		const { name, value } = target;
		const validationRules = this.validationRules[name];

		if ( validationRules ) {
			const error = validateField(value, validationRules);
			target.dataset.error = error || '';
			target.nextElementSibling.textContent = error;
		}

		if ( name === 'sms_notif' ) {
			this.checkSMSNotif();
		}

		if (!this.formSuccess.classList.contains('hidden')) {
			this.formSuccess.classList.add('hidden');
		}
	}

	// Make phone required if sms_notif is on
	checkSMSNotif() {
		this.validationRules.phone = [
			'phone',
			this.form.sms_notif.checked ? 'required' : '',
		].filter(Boolean);
	}

	onFormSubmit(e) {
		e.preventDefault();

		// Another round of validation
		const hasError = this.fields.map(target => {
			this.onFormChange({ target });
			return target;
		}).some(element => element.dataset.error);

		if ( hasError ) {
			return;
		}

		// Extract form data into url params
		const formData = [...new FormData(this.form).entries()].reduce((data, entry) => {
			data.push(entry.join('='));
			return data;
		}, []).join('&');

		this.submitData(formData);
	}

	formatFieldValue(field, value) {
		switch (field) {
			case 'sms_notif':
				return value ? 1 : 0;
			default:
				return value;
		}
	}

	async submitData(data) {
		const method = 'POST';
		const headers = {
			'Content-Type': 'application/x-www-form-urlencoded'
		};
		const body = data;

		try {
			const response = await fetch(ENDPOINT, { method, headers, body });
			const json = await response.json();

			this.formSuccess.classList.remove('hidden');
			this.form.reset();

			console.log({ json });
		} catch (err) {
			console.warn(err);
		}
	}

	static initializeInstances() {
		const [...elements] = document.querySelectorAll(CampaignForm.selector);

		elements.forEach(element => {
			const instance = new CampaignForm(element);
			instance.init();
		});
	}
}
