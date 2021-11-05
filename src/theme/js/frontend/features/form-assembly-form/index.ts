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

		// Add necessary selectors for FloatingLabelInput initialization
		this.children?.inputFields.forEach((inputField: HTMLElement) => {
			const options = {};
			const requiredlabel = inputField.querySelector('.reqMark');

			// Add asterisk on required label
			if (requiredlabel) {
				requiredlabel.innerHTML += '&nbsp;*';
			}

			inputField.classList.add('floating-label-input');

			FloatingLabelInput.initializeWithElement(inputField, options);
		});
	}

	reset() {
		this.onReset();
	}

	onSubmit(e: Event) {
		e.preventDefault();
		const form = e.currentTarget as HTMLFormElement;

		// Delayed process in order for FormAssembly's validator to fire first
		setTimeout(async () => {
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
		}, 100);
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
}

export default FormAssemblyForm;
