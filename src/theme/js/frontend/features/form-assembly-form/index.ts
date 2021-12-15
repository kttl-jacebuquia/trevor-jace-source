import Component from '../../Component';
import FloatingLabelInput from '../floating-label-input';
import { WPAjax } from '../wp-ajax';

class FormAssemblyForm extends Component {
	children?: {
		inputFields: HTMLElement[];
		conditionalSections: HTMLElement[];
		submitButton: HTMLButtonElement;
		dynamicFields: HTMLElement[];
		requiredFields: HTMLElement[];
		recaptchaResponse: HTMLTextAreaElement;
	};

	requiredFields: HTMLElement[] = [];

	static ajaxAction = 'form_assembly';

	static customEventPrefix: string = 'fa-';

	static selector = '.wForm form';

	static children = {
		inputFields: ['.oneField'],
		conditionalSections: ['.section[data-condition]'],
		submitButton: '#submit_button',
		dynamicFields: ['[data-condition'],
		recaptchaResponse: '.g-recaptcha-response',
	};

	afterInit() {
		this.element.addEventListener('change', (e) => this.onChange());
		this.element.addEventListener('reset', () => this.onReset());
		this.element.addEventListener('submit', (e) => this.onSubmit(e));

		// Remove autocomplete/autofill
		this.element.setAttribute('autocomplete', 'off');

		// Get requried fields
		this.requiredFields =
			this.children?.inputFields.filter((inputField) =>
				inputField.querySelector('.required')
			) || [];

		// Add necessary selectors for FloatingLabelInput initialization
		this.children?.inputFields.forEach((inputField: HTMLElement) => {
			const options: { [key: string]: any } = {};
			const requiredlabel = inputField.querySelector('.reqMark');

			// Add asterisk on required label
			if (requiredlabel) {
				requiredlabel.innerHTML += '&nbsp;*';
			}

			inputField.classList.add('floating-label-input');

			FloatingLabelInput.initializeWithElement(inputField, options);
		});

		// Cleanup default TFA styles
		this.removeTFAStylesheets();

		// Handle resize
		this.handleResize();

		// Handle legends
		this.handleLegends();

		// Handle form mutation
		this.handleMutation();

		// Handle Recaptcha, if there is any
		this.handleRecaptcha();

		// Initially run onchange
		this.onChange();
	}

	reset() {
		this.onReset();
	}

	getFormData() {
		const [...formDataEntries] = new FormData(
			this.element as HTMLFormElement
		).entries();
		const formData = formDataEntries.reduce(
			(data: { [key: string]: any }, [key, value]) => {
				if (value) {
					data[key] = value;
				}
				return data;
			},
			{}
		);

		return formData;
	}

	onChange() {
		const formData = this.getFormData();
		const recaptchaResponse: HTMLTextAreaElement | null =
			this.element.querySelector(
				FormAssemblyForm.children.recaptchaResponse
			);

		// Check for visible required fields
		// If there are missing user input
		let willEnableSubmit = !this.requiredFields.some((inputField) => {
			if (!inputField.offsetParent) {
				return false;
			}

			// Get input field, account for checkbox options
			const fields: HTMLInputElement[] | HTMLElement = Array.from(
				inputField.querySelectorAll('[name]')
			);

			const isInputProvided = fields.some(
				(inputElement) => inputElement.name in formData
			);

			return !isInputProvided;
		});

		// Check for recaptcha
		willEnableSubmit =
			willEnableSubmit &&
			(recaptchaResponse ? Boolean(recaptchaResponse.value) : true);

		this.toggleSubmitButton(willEnableSubmit);
	}

	onSubmit(e: Event) {
		e.preventDefault();
		const form = e.currentTarget as HTMLFormElement;

		this.preValidation();

		// Delayed process in order for FormAssembly's validator to fire first
		setTimeout(async () => {
			this.postValidation();

			const errorFields = form.querySelectorAll('.errFld');

			// Don't submit if there's any error
			if (errorFields.length) {
				return;
			}

			// Don't submit if submit button is not disabled
			// This might happen when there's there's an error alert
			// but no actual error message in the form
			if (this.children?.submitButton?.disabled) {
				return;
			}

			const formData = [...new window.FormData(form).entries()].reduce(
				(all: { [key: string]: any }, [key, value]: [string, any]) => {
					all[key] = value;
					return all;
				},
				{}
			);

			const response = await WPAjax({
				action: FormAssemblyForm.ajaxAction,
				data: formData,
			});

			if (/success/i.test(response?.status || '')) {
				this.onSubmitSuccess();
			}
		}, 300);
	}

	toggleSubmitButton(isEnabled: boolean = false) {
		if (isEnabled) {
			this.children?.submitButton.removeAttribute('disabled');
			this.children?.submitButton.classList.toggle(
				'disabled',
				!isEnabled
			);
		} else {
			this.children?.submitButton.setAttribute('disabled', 'true');
			this.children?.submitButton.classList.toggle(
				'disabled',
				!isEnabled
			);
		}
	}

	onSubmitSuccess() {
		this.emit('submit');
	}

	onReset() {
		// Reset each field
		this.children?.inputFields.forEach((inputField: HTMLElement) => {
			const errorMsg = inputField.querySelector('.errMsg');

			inputField.classList.remove('floating-label-input--activated');

			// Remove error messages
			errorMsg?.remove();
		});

		// Reset conditional fields
		this.children?.conditionalSections.forEach((section: HTMLElement) => {
			section.classList.add('offstate');
		});

		this.emit('reset');
	}

	// Runs before the FormAssembly validator kicks in
	preValidation() {
		// Ensures that we clear out the manual errors we inserted,
		// which are possibly left out by the FormAssembly script
		this.clearOutErrorMessages();
	}

	clearOutErrorMessages() {
		const errorMessages = this.element.querySelectorAll(
			'.errMsg'
		) as unknown as FormAssemblyElementCollection;

		errorMessages.forEach((errorMessage: FormAssemblyElement) => {
			errorMessage.innerHTML = '';
		});
	}

	// For some FormAssembly embeds,
	// Not all errors are showing up on empty required fields.
	// We'll be manually adding them as workaround
	postValidation() {
		const requiredLabels = this.element.querySelectorAll(
			'label.reqMark'
		) as unknown as FormAssemblyElementCollection;
		const requiredFields = requiredLabels.map(
			(label: FormAssemblyElement) => label?.parentElement
		) as Array<FormAssemblyElement | null>;
		const offstateSections = this.element.querySelectorAll(
			'.section.offstate'
		) as unknown as FormAssemblyElementCollection;

		const requiredFieldsWithoutErrors = requiredFields.filter(
			(requiredField: FormAssemblyElement | null) => {
				if (
					!requiredField ||
					requiredField.classList.contains('offstate') ||
					offstateSections.some((section) =>
						section.contains(requiredField)
					)
				) {
					return false;
				}

				const field:
					| HTMLInputElement
					| HTMLTextAreaElement
					| HTMLSelectElement
					| null =
					requiredField?.querySelector('[name^="tfa_"]') || null;
				const errorMsg: HTMLElement | null =
					requiredField?.querySelector('.errMsg') || null;
				return !field?.value && !errorMsg;
			}
		);

		// Manually add errors
		if (requiredFieldsWithoutErrors.length) {
			requiredFieldsWithoutErrors.forEach(
				(requiredField: FormAssemblyElement | null) => {
					if (requiredField) {
						const [id] = requiredField.id.split('-');
						const errorMsg = `<div id="${id}-E" class="errMsg" tabindex="-1"><span>This field is required.</span></div>`;
						requiredField.insertAdjacentHTML('beforeend', errorMsg);
						requiredField.classList.add('errFld');
					}
				}
			);
		}
	}

	handleResize() {
		const resizeObserver = new window.ResizeObserver(
			([{ target }]: ResizeObserverEntry[]) => {
				// Make sure resize is only triggered by the form element
				// and not its children
				if (target === this.element) {
					this.onResize();
				}
			}
		);
		resizeObserver.observe(this.element);
	}

	/* Handle form resize */
	onResize() {
		/* Will store the maximum scaled height from the fields */
		let maxScaledHeight = 0;

		/* Check non-checkbox/radio input fields */
		this.children?.inputFields.forEach((inputGroup: HTMLElement) => {
			// Exclude checkbox and radio from computation
			if (/checkbox|radio/i.test(inputGroup.dataset.inputType || '')) {
				return;
			}

			const label = inputGroup.querySelector(
				FloatingLabelInput.children.label
			) as HTMLElement;
			const dummyLabelActive: HTMLElement | null =
				inputGroup.querySelector(
					FloatingLabelInput.children.dummyLabelActive
				);
			const labelHeightReference = dummyLabelActive || label;
			const scaledLabelHeight =
				labelHeightReference?.getBoundingClientRect().height || 0;

			// Apply individual label heights
			inputGroup.style.setProperty(
				'--active-label-height',
				scaledLabelHeight + 'px'
			);

			// Save maximum label height
			maxScaledHeight =
				scaledLabelHeight > maxScaledHeight
					? scaledLabelHeight
					: maxScaledHeight;
		});

		this.element.style.setProperty(
			'--max-scaled-label-height',
			`${maxScaledHeight}px`
		);
	}

	// Attaches event listener
	on(eventName: 'submit' | 'reset' | 'change', callback: (args: any) => any) {
		this.element.addEventListener(
			`${FormAssemblyForm.customEventPrefix}${eventName}`,
			callback
		);
	}

	// Emits custom event
	emit(eventName: 'submit' | 'reset' | 'change') {
		const customEvent = new window.CustomEvent(
			`${FormAssemblyForm.customEventPrefix}${eventName}`
		);
		this.element.dispatchEvent(customEvent);
	}

	// Removes TFA stylesheets.
	// Some stylesheets have !important in different places that makes
	// style housekeeping difficult, so removing them instead
	removeTFAStylesheets() {
		[...document.querySelectorAll('link[href*="tfaforms"]')].forEach(
			(link) => link.remove()
		);
	}

	// Replacing legend elements with div instead, in order to
	// workaround style inconsistencies with legend in Safari
	handleLegends() {
		const legends: HTMLLegendElement[] = [
			...this.element.querySelectorAll('legend'),
		];

		legends.forEach((legend) => {
			const div = document.createElement('div');
			div.id = legend.id;
			div.textContent = legend.textContent;
			div.classList.add('legend');

			legend.insertAdjacentElement('beforebegin', div);
			legend.remove();
		});
	}

	onMutation() {
		this.onChange();
	}

	handleRecaptcha() {
		setTimeout(() => {
			const recaptchaResponse = this.element.querySelector('.captcha');
			const response = recaptchaResponse?.querySelector(
				'.g-recaptcha-response'
			);
			console.log({ recaptchaResponse, response });
		}, 1000);
	}

	// Listens to form mutation:
	// - When grecaptcha is rendered/responded, etc.
	handleMutation() {
		const observer = new window.MutationObserver(
			this.onMutation.bind(this)
		);
		observer.observe(this.element, {
			childList: true,
			attributes: true,
		});
	}
}

export default FormAssemblyForm;
