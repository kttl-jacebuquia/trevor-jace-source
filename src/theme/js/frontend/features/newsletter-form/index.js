import Component from 'theme/js/frontend/Component';

const NEWSLETTER_AJAX_ENDPOINT = '/wp-admin/admin-ajax.php?action=mailchimp';

export default class NewsletterForm extends Component {
	// Defines the element selector which will initialize this component
	static selector = '.newsletter-form';

	// Defines children that needs to be queried as part of this component
	static children = {
		emailInput: 'input[type="email"]',
		message: '.newsletter-form__message',
		submit: '[type="submit"]',
	};

	// Defines event handlers to attach to children
	eventHandlers = {
		emailInput: {
			input: this.onEmailInput,
		},
	};

	// Defines initial State
	state = {
		email: '',
		message: '',
		submitted: false,
		submitting: false,
	};

	// Will be called upon component instantiation
	afterInit() {
		this.element.addEventListener('submit', this.onSubmit.bind(this));
	}

	onEmailInput() {
		this.setState({
			email: this.children.emailInput.value,
			message: '',
		});
	}

	onSubmit(event) {
		// Prevent page reload
		event.preventDefault();

		// Should not send if email input is empty
		if (!this.state.email) {
			this.showError();
			return;
		}

		this.setState({
			// Clear out error message.
			message: '',
			// Toggle submitting state to true
			submitting: true,
		});

		this.sendEmail();
	}

	async sendEmail() {
		// Submit email to wp-ajax endpoint for mailchimp
		const response = await fetch(NEWSLETTER_AJAX_ENDPOINT, {
			method: 'post',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: `email=${this.state.email}`,
		});

		// Check if we get a success response
		const { title, status } = await response.json();

		// Error response gives out a title
		if (title) {
			this.setState({
				message: this.getMessageByErrorTitle(title),
			});
			console.warn({ status, title });
		} else {
			this.setState({
				message: 'Thank you for your submission!',
			});
		}

		// Apply post-send states
		this.setState({
			submitting: false,
			submitted: !title,
		});
	}

	showError() {
		this.showMessage('Please enter a valid email address.');
	}

	showMessage(message = '') {
		if ( !message ) {
			return;
		}

		this.setState({ message });
	}

	// Provides informative error message in FE according to response title
	getMessageByErrorTitle(title) {
		switch (true) {
			case /exists/i.test(title):
				return 'Email is already subscribed.';
			default:
				return 'Please enter a valid email address.';
		}
	}

	// Triggers when state is change by calling this.setState()
	async componentDidUpdate() {
		const {
			submitting = false,
			submitted = false,
			message = '',
		} = this.state;

		// Submitting state
		this.element.classList.toggle(
			'newsletter-form--submitting',
			submitting
		);

		// Submitted state
		this.element.classList.toggle('newsletter-form--submitted', submitted);

		// Disable submit if submitting/submitted
		if (submitting || submitted) {
			this.children.submit.setAttribute('disabled', true);
		} else {
			this.children.submit.removeAttribute('disabled');
		}

		// Error message, if any
		this.children.message.textContent = message;
		if (message) {
			this.element.classList.add('newsletter-form--message');
		} else {
			this.element.classList.remove('newsletter-form--message');
		}
	}
}

// Uncomment this section if this component is intended
// to initialize on DOM load.
NewsletterForm.init();
