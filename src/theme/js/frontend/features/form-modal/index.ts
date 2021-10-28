import Component from '../../Component';
import FloatingLabelInput from '../floating-label-input';
import { WPAjax } from '../wp-ajax';

class FormModal extends Component {
	children?: {
		description: HTMLElement;
		formAssemblyForm: HTMLFormElement;
	};

	static selector = '.form-modal';

	static children = {
		description: '.form-modal__description',
		// FormAssembly
		formAssemblyForm: '.wForm form',
	};

	afterInit() {
		if (this.children?.formAssemblyForm) {
			this.initDevInquiryForm();
		}
	}

	// Incorporate FloatingLabelInputs to FormAssembly DevInquiryForm
	initDevInquiryForm() {
		const embeddedForm = this.children?.formAssemblyForm;

		const [...inputFields] = Array.from(
			embeddedForm.querySelectorAll('.oneField')
		);

		embeddedForm.addEventListener('reset', () =>
			this.onFormAssemblyReset(embeddedForm)
		);

		// Add necessary selectors for FloatingLabelInput initialization
		inputFields.forEach((inputField) => {
			const options = {};
			const requiredlabel = inputField.querySelector('.reqMark');

			// Add asterisk on required label
			if (requiredlabel) {
				requiredlabel.innerHTML += '&nbsp;*';
			}

			inputField.classList.add('floating-label-input');

			FloatingLabelInput.initializeWithElement(inputField, options);
		});

		embeddedForm.addEventListener(
			'submit',
			this.onDevInquirySubmit.bind(this)
		);
	}

	onFormAssemblyReset(form: HTMLFormElement) {
		const inputFields: HTMLElement[] = Array.from(
			this.children?.formAssemblyForm.querySelectorAll('.oneField')
		);

		inputFields.forEach((floatingLabelElement: HTMLElement) => {
			floatingLabelElement.classList.remove(
				'floating-label-input--activated'
			);
		});
	}

	async onDevInquirySubmit(e) {
		e.preventDefault();

		// Delayed process in order for FormAssembly's validator to fire first
		setTimeout(async () => {
			const errorFields =
				this.children?.formAssemblyForm.querySelectorAll('.errFld');

			// Don't submit if there's any error
			if (errorFields.length) {
				return;
			}

			const formData = [
				...new window.FormData(
					this.children?.formAssemblyForm
				).entries(),
			].reduce((all, [key, value]) => {
				all[key] = value;
				return all;
			}, {});

			const response = await WPAjax({
				action: this.children?.formAssemblyForm.action,
				data: formData,
			});

			if (/success/i.test(response?.status || '')) {
				this.onFormSubmitSuccess();
			}
		}, 100);
	}

	onFormSubmitSuccess() {
		if (this.children?.description) {
			this.children.description.outerHTML = `<p>Thank you for your submission!</p>`;
		}
	}
}

FormModal.init();
