import Component from '../../Component';
import FormAssemblyForm from '../form-assembly-form';

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
			this.initFormAssemblyForm();
		}
	}

	// Incorporate FloatingLabelInputs to FormAssembly DevInquiryForm
	initFormAssemblyForm() {
		const formAssemblyForm = FormAssemblyForm.initializeWithElement(
			this.children?.formAssemblyForm as HTMLFormElement
		);
		formAssemblyForm.on('submit', () => this.onFormSubmitSuccess());
	}

	onFormSubmitSuccess() {
		if (this.children?.description) {
			this.children.description.innerHTML = `<p>Thank you for your submission!</p>`;
		}

		// Remove the form after submission
		this.children?.formAssemblyForm?.remove();
	}
}

FormModal.init();
