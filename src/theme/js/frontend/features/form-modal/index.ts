import $ from 'jquery';
import Component from '../../Component';
import FormAssemblyForm from '../form-assembly-form';

class FormModal extends Component {
	children?: {
		description: HTMLElement;
		formAssemblyContainer: HTMLElement;
		formAssemblyForm: HTMLFormElement;
	};

	formAssemblyForm?: FormAssemblyForm;

	static selector = '.form-modal';

	static children = {
		description: '.form-modal__description',
		// FormAssembly
		formAssemblyContainer: '.wFormContainer',
		formAssemblyForm: '.wForm form',
	};

	afterInit() {
		this.handleModal();

		if (this.children?.formAssemblyForm) {
			this.initFormAssemblyForm();
		}
	}

	handleModal() {
		$(this.element).on('modal-close', this.onModalClose.bind(this));
	}

	// Incorporate FloatingLabelInputs to FormAssembly DevInquiryForm
	initFormAssemblyForm() {
		this.formAssemblyForm = FormAssemblyForm.initializeWithElement(
			this.children?.formAssemblyForm as HTMLFormElement
		);
		this.formAssemblyForm?.on('submit', () => this.onFormSubmitSuccess());
	}

	onFormSubmitSuccess() {
		if (this.children?.description) {
			this.children.description.innerHTML = `<p>Thank you for your submission!</p>`;
		}

		// Remove the form after submission
		this.children?.formAssemblyContainer?.remove();
	}

	onModalClose() {
		// Reset next page inputs
		Array.from(this.element.querySelectorAll('input,select')).forEach(
			(input: HTMLInputElement | HTMLSelectElement) => {
				if (input instanceof window.HTMLSelectElement) {
					input.value = '';
					return;
				}

				switch (input.type) {
					case 'text':
					case 'number':
						input.value = '';
						break;
					case 'radio':
					case 'checkbox':
						input.checked = false;
						break;
				}
			}
		);

		// Reset next page formss
		this.formAssemblyForm?.reset();
	}
}

FormModal.init();
