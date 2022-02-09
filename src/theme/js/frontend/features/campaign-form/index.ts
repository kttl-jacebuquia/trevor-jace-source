import Component from '../../Component';
import { pushFormData } from '../gtm';

const ENDPOINT = '/wp-admin/admin-ajax.php?action=phone2action';
const PHONE_VALIDATION_RULES = ['phone', 'length-of:number:10'];

const emailRegex =
	/[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/i;

const validationMessages = {
	required: '* This is a required field.',
	phone: 'Please use a valid phone number.',
	number: 'This field must be number',
	email: 'Please use a valid email',
};

// Just a simple validation helper, nothing fancy
// Returns a message if failed, none if success
const validateField = (value: string, rules: string[]) => {
	const validationRules = [...rules]; // Prevent mutation

	while (validationRules.length) {
		const [rule, ruleParameter, ...extraArgs] = validationRules
			? (validationRules.shift() as string).split(':')
			: [];
		switch (rule) {
			case 'required':
				if (!value) {
					return validationMessages.required;
				}
				break;
			case 'phone':
				if (/[^0-9\-\(\)\s]/g.test(value)) {
					return validationMessages.phone;
				}
				break;
			case 'number':
				if (/[^0-9]/.test(value)) {
					return validationMessages.number;
				}
				break;
			case 'email':
				if (!emailRegex.test(value)) {
					return validationMessages.email;
				}
				break;
			case 'max':
				const max = Number(ruleParameter);
				if (typeof value === 'string') {
					return value.length > max
						? `Maximum allowed characters is ${max}`
						: null;
				}
				if (typeof value === 'number') {
					return value > max
						? `Maximum allowed number is ${max}`
						: null;
				}
				break;
			case 'min':
				const min = Number(ruleParameter);
				if (typeof value === 'string') {
					return value.length < min
						? `Minimum allowed characters is ${min}`
						: null;
				}
				if (typeof value === 'number') {
					return value < min
						? `Minimum allowed number is ${min}`
						: null;
				}
				break;
			// String length
			case 'length':
				const length = Number(ruleParameter);
				return String(value).length !== length
					? `Character length must be ${length}`
					: null;
			// Length of specified character group
			// e.g., length-of:number,letter
			// e.g., length-of:letter
			case 'length-of':
				if (!value) {
					continue;
				}
				const requiredLength = Number(extraArgs[0] || '0');
				const characterGroups = ruleParameter.split(',');
				const patternString = characterGroups
					.map((characterGroup: string): string | null => {
						if (characterGroup === 'letter') {
							return '[a-zA-Z]+';
						}
						if (characterGroup === 'number') {
							return '[0-9]+';
						}
						return null;
					})
					.filter(Boolean)
					.join('|');
				const matchedChars = (
					String(value).match(new RegExp(patternString, 'g')) || []
				).join('');
				const charsLength = matchedChars.length;
				return charsLength !== requiredLength
					? `Character length must be ${requiredLength}`
					: null;
		}
	}
};

export default class CampaignForm extends Component {
	static selector = '.join-the-campaign-form';

	formSelector = '.join-the-campaign-form__form';
	successSelector = '.join-the-campaign-form__success';
	formLabelSelector = '.join-the-campaign-form__phone-label';
	validationRules: { [key: string]: any } = {
		fullname: ['required', 'max:30'],
		email: ['required', 'email', 'max:30'],
		phone: PHONE_VALIDATION_RULES, // dynamically adds required if sms_notif is on
		zipcode: ['required', 'number', 'min:4', 'max:5'],
	};
	formGTMName = 'join the campaign';
	form: HTMLFormElement | null = null;
	formSuccess: HTMLElement | null = null;
	fields: HTMLInputElement[] = [];
	phoneLabel: HTMLElement | null = null;

	init() {
		this.initializeForm();
	}

	initializeForm() {
		this.form = this.element.querySelector(this.formSelector);
		this.formSuccess = this.element.querySelector(this.successSelector);
		this.phoneLabel = this.element.querySelector(this.formLabelSelector);
		this.fields = Array.from(this.form?.elements || []).filter(
			(element) => (element as HTMLInputElement).name
		) as HTMLInputElement[];

		// Save phone label's original text
		if (this.phoneLabel) {
			this.phoneLabel.dataset.originalText =
				this.phoneLabel?.textContent?.trim();
		}

		if (this.form) {
			this.form.addEventListener('change', this.onFormChange.bind(this));
			this.form.addEventListener('submit', this.onFormSubmit.bind(this));
			this.checkSMSNotif();
		}
	}

	onFormChange(event: Event) {
		const target = event.target as HTMLInputElement;
		// Validate changed field
		const { name, value } = target;
		const validationRules = this.validationRules[name];

		// Extract only numbers for phone, for validation
		const sanitizedValue =
			name === 'phone' ? (value.match(/[0-9]*/g) || []).join('') : value;

		if (validationRules) {
			const error = validateField(sanitizedValue, validationRules);
			target.dataset.error = error || '';

			if (target.parentElement?.nextElementSibling) {
				target.parentElement.nextElementSibling.textContent =
					error || '';
			}
		}

		if (name === 'sms_notif') {
			this.checkSMSNotif();
		}

		if (!this.formSuccess?.classList.contains('hidden')) {
			this.formSuccess?.classList.add('hidden');
		}
	}

	// Make phone required if sms_notif is on
	checkSMSNotif() {
		this.validationRules.phone = [
			...PHONE_VALIDATION_RULES,
			this.form?.sms_notif.checked ? 'required' : null,
		].filter(Boolean);

		if (this.phoneLabel) {
			this.phoneLabel.textContent =
				this.phoneLabel.dataset.originalText +
				(this.form?.sms_notif.checked ? '*' : '');
		}
	}

	onFormSubmit(event: Event) {
		event.preventDefault();

		// Another round of validation
		const hasError = this.fields
			.map((target) => {
				this.onFormChange({ target } as unknown as InputEvent);
				return target;
			})
			.some((element) => element.dataset.error);

		if (hasError) {
			pushFormData(this.formGTMName, false);

			return;
		}

		// Extract form data into url params
		const formData = (
			this.form ? [...new window.FormData(this.form).entries()] : []
		)
			.reduce((data: string[], entry) => {
				data.push(entry.join('='));
				return data;
			}, [])
			.join('&');

		this.submitData(formData);
	}

	formatFieldValue(field: string, value: FormDataEntryValue) {
		switch (field) {
			case 'sms_notif':
				return value ? 1 : 0;
			default:
				return value;
		}
	}

	async submitData(data: string) {
		const method = 'POST';
		const headers = {
			'Content-Type': 'application/x-www-form-urlencoded',
		};
		const body = data;

		try {
			await window.fetch(ENDPOINT, { method, headers, body });
			this.formSuccess?.classList.remove('hidden');
			this.form?.reset();

			pushFormData(this.formGTMName, true);
		} catch (err) {
			pushFormData(this.formGTMName, false);

			console.warn(err);
		}
	}

	static initializeInstances() {
		const [...elements] = document.querySelectorAll(CampaignForm.selector);

		elements.forEach((element) => {
			const instance = new CampaignForm(element as HTMLElement);
			instance.init();
		});
	}
}
