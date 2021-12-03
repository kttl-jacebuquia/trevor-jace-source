import Component from '../../Component';
import FloatingLabelInput from '../floating-label-input';
import { WPAjax } from '../wp-ajax';

class FormAssemblyForm extends Component {
	children?: {
		inputFields: HTMLElement[];
		conditionalSections: HTMLElement[];
		submitButton: HTMLButtonElement;
	};

	static ajaxAction = 'form_assembly';

	static customEventPrefix: string = 'fa-';

	static selector = '.wForm form';

	static children = {
		inputFields: ['.oneField'],
		conditionalSections: ['.section[data-condition]'],
		submitButton: '#submit_button',
	};

	afterInit() {
		this.element.addEventListener('reset', () => this.onReset());
		this.element.addEventListener('submit', (e) => this.onSubmit(e));

		// Remove autocomplete/autofill
		this.element.setAttribute('autocomplete', 'off');

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
	}

	reset() {
		this.onReset();
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
			if (!this.children?.submitButton?.disabled) {
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
}

export default FormAssemblyForm;
