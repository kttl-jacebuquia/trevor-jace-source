import $ from 'jquery';
import FormAssemblyForm from '../form-assembly-form';
import Modal from '../modal';

interface FundraiserQuizOptions {
	[key: string]: any;
}

interface ShowFundraiserQuizOptions {
	initialVertex?: string;
}

export default class FundraiserQuiz {
	selector: string = '.fundraiser-quiz';
	options?: FundraiserQuizOptions;
	totalPages: number = 1;
	currentPage: number = 0;
	ajaxAction?: string;
	classes: string = 'visible static';
	containers: { [key: string]: string } = {};
	mainVerteces: string[] = [];
	verteces: string[] = [];
	prevVertexStack?: string[] = [];
	currentVertexStack?: string[] = [];
	answers?: string[] = [];
	graph?: any;

	// jQuery elements
	parentContainer?: JQuery;
	backBtn?: JQuery;
	choices?: JQuery;
	modalRoot?: JQuery;
	modalContainer?: JQuery;
	form?: JQuery;
	paginationContainer?: JQuery;
	currentPageContainer?: JQuery;
	totalPageContainer?: JQuery;
	$devInquirySlide?: JQuery;

	// HTMLElements
	choicesForms?: HTMLFormElement[];
	devInquiryForm?: HTMLElement;
	devInquiryFieldsContainer?: HTMLElement;

	/**
	 *
	 * @param {Object} options Fundraiser Options
	 *
	 * Available options
	 * initialVertex - Initial vertex to show when modal appears
	 * single        - If true, hides pagination and back buttons
	 */
	constructor(options: FundraiserQuizOptions) {
		this.selector = '.fundraiser-quiz';
		this.parentContainer = $(`${this.selector}.container`);
		this.backBtn = $(`${this.selector}__back-btn`);
		this.choices = $(`${this.selector}__radio-btn`);
		this.modalRoot = $('#js-fundraiser-quiz');
		this.modalContainer = $('#js-fundraiser-quiz .modal-container');
		this.form = $(`form${this.selector}`);
		this.paginationContainer = $(`${this.selector}__pagination`);
		this.currentPageContainer = $(`${this.selector}__current-page`);
		this.totalPageContainer = $(`${this.selector}__total-page`);
		this.totalPages = 1;
		this.currentPage = 0;
		this.options = options;

		this.choicesForms = Array.from(
			this.parentContainer
				?.get(0)
				?.querySelectorAll(`${this.selector}__choices-form`) || []
		);
		this.ajaxAction = this.parentContainer.data('ajax-action');
		this.$devInquirySlide = this.parentContainer.find(
			'[data-vertex="form"]'
		);
		this.devInquiryForm =
			this.$devInquirySlide?.get(0)?.querySelector('form') || undefined;
		this.devInquiryFieldsContainer = this.$devInquirySlide
			.find('.fundraiser-quiz__fields')
			.get(0);

		this.containers = {
			donate: `${this.selector}--step-one`,
			streaming: `${this.selector}--step-one`,
			personal: `${this.selector}--step-one`,
			social: `${this.selector}--step-one`,
			form: `${this.selector}--form`,
			create: `${this.selector}--create-fundraiser`,
			collect: `${this.selector}--collect-donations`,
			who: `${this.selector}--who-is-fundraising`,
			gathering: `${this.selector}--gathering`,
		};

		this.mainVerteces = ['donate', 'streaming', 'personal', 'social'];
		this.verteces = [
			'donate',
			'streaming',
			'personal',
			'social',
			'form',
			'create',
			'collect',
			'who',
			'gathering',
		];
		this.graph = new window.Map();
		this.prevVertexStack = [];
		this.currentVertexStack = [];
		this.answers = [];

		this.classes = 'visible static';

		this.createGraph();

		/**
		 * Initialize the radio button events
		 */
		// if (this.choices.length) {
		// 	this.choices.on('click' as any, (e) => {
		// 		this.handleChoiceClick(e.target);
		// 	});
		// }

		this.backBtn.on('click', () => {
			this.displayPreviousPage();
		});

		this.form.on('submit' as any, (e) => {
			const [lastVertex] = this.currentVertexStack?.slice(-1);

			if (lastVertex !== 'form') {
				e.preventDefault();
			}
		});

		// Initialize choices forms
		this.choicesForms?.forEach(this.initChoicesForm.bind(this));

		// Initialize other features
		this.initDevInquiryForm();
		this.handleModal();
	}

	show(options: ShowFundraiserQuizOptions = {}) {
		// Default initial step
		let $initialStepContent = $(`${this.selector}--step-one`);

		// Clear inputs
		this.clearContentInputs(`${this.selector}--step-one`);

		this.changeContainerBackground(false);

		// Reset counter
		this.currentVertexStack = [];

		// Override initial step if supplied in options
		if (options.initialVertex) {
			const $content = $(
				document.querySelector(
					`[data-vertex="${options.initialVertex}"]:not(input)`
				)
			);
			if ($content.length) {
				$initialStepContent.hide();
				$initialStepContent = $content;
				this.clearContentInputs(options.initialVertex);
			}

			this.changeContainerBackground(options.initialVertex);
		}

		// Clear inline styles
		$initialStepContent.removeAttr('style');
		$initialStepContent.removeClass('hidden');

		this.choices?.prop('checked', false);
		this.backBtn?.hide();
		$(`${this.selector}--steps`).removeClass(this.classes);
		this.paginationContainer?.hide();

		setTimeout(() => {
			$initialStepContent.fadeIn(500, () => {
				$initialStepContent.addClass(this.classes);
			});
		}, 100);
	}

	// Incorporate FloatingLabelInputs to FormAssembly DevInquiryForm
	initDevInquiryForm() {
		const embeddedForm = this.devInquiryForm as HTMLFormElement;

		if (embeddedForm) {
			const formAssemblyForm =
				FormAssemblyForm.initializeWithElement(embeddedForm);

			formAssemblyForm.on('submit', () => this.onFormSubmitSuccess());
		}
	}

	initChoicesForm(choicesForm: HTMLFormElement) {
		// When user selects a choice
		choicesForm.addEventListener(
			'change',
			this.onChoiceFormChange.bind(this)
		);

		// When user clicks "Next Question"
		choicesForm.addEventListener(
			'submit',
			this.onChoiceFormSubmit.bind(this)
		);
	}

	onChoiceFormChange(event: Event) {
		const choicesForm = event.currentTarget as HTMLFormElement;
		const submitButton = choicesForm?.querySelector('[type="submit"]');

		submitButton?.removeAttribute('disabled');
		submitButton?.removeAttribute('aria-hidden');
	}

	onChoiceFormSubmit(event: Event) {
		event.preventDefault();

		const choicesForm = event.currentTarget as HTMLFormElement;
		const [keyName] = new FormData(choicesForm).keys();
		const selection = Array.from(
			choicesForm[keyName] as HTMLInputElement[]
		).find(({ checked }) => checked);

		// Display next page
		this.displayNextPage(selection);
	}

	onFormSubmitSuccess() {
		if (this.devInquiryFieldsContainer) {
			this.devInquiryFieldsContainer.outerHTML = '';
		}
	}

	handleChoiceClick(btn: HTMLInputElement) {
		const choicesForm = btn.form;
		const choicesSubmitBtn = choicesForm?.querySelector('[type="submit"]');

		// Enable submit
		choicesSubmitBtn?.removeAttribute('disabled');
		choicesSubmitBtn?.removeAttribute('aria-hidden');
	}

	displayNextPage(btn) {
		const vertex = btn.dataset.vertex;
		const [nextVertex] = this.graph.get(vertex);
		this.answers.push(btn.value);
		this.currentVertexStack.push(nextVertex);
		this.handleBackButtonDisplay(nextVertex);

		if (this.mainVerteces.includes(vertex)) {
			this.totalPages = 1; // reset the number of pages.
			this.computeTotalPageNumber(vertex);
		}

		this.computeCurrentPageNumber();
		this.paginationContainer.fadeIn();
		// Get the new content
		const $content = $(
			`[data-vertex="${nextVertex}"]:not(input)`,
			this.selector
		);

		if ($content.length) {
			const $oldContent = $(this.containers[vertex], this.selector);
			this.prevVertexStack.push(vertex);
			this.handleNextContentRadioBtns($content);

			// Hide old content
			$oldContent.fadeOut(400, () => {
				this.changeContainerBackground(nextVertex);
				$content.addClass(this.classes);
			});

			this.clearContentInputs(nextVertex);

			// Show new content
			$content.removeClass('hidden').fadeIn({
				done() {
					setTimeout(() => {
						$content.get(0).focus();
					}, 100);
				},
			});
		}
	}

	clearContentInputs(vertexOrSelector: string) {
		const selector = /[^a-z_0-9\-]/gi.test(vertexOrSelector)
			? vertexOrSelector
			: `[data-vertex="${vertexOrSelector}"]:not(input)`;
		const $content = (this.parentContainer as JQuery).find(selector);

		// Reset next page inputs
		$content.find('input,select').each((index, input) => {
			switch (input.type) {
				case 'text':
				case 'number':
					input.value = '';
					break;
				case 'radio':
				case 'checkbox':
					input.checked = false;
					break;
				case 'submit':
					input.setAttribute('disabled', 'true');
					input.setAttribute('aria-hidden', 'true');
					break;
			}
		});

		// Reset next page formss
		$content.find('form')?.get(0)?.reset();
	}

	computeCurrentPageNumber() {
		this.currentPage = 1 + this.currentVertexStack.length;
		this.currentPageContainer.html(this.currentPage);
	}

	computeTotalPageNumber(vertex) {
		let nextVertex = this.graph.get(vertex);

		while (nextVertex.length) {
			const [lastItem] = nextVertex.slice(-1);

			nextVertex = this.graph.get(lastItem);

			++this.totalPages;
		}

		this.totalPageContainer.html(this.totalPages);
	}

	/**
	 * Clears all the radio buttons of the next content.
	 *
	 * @param {JQuery.JQuery} $content
	 */
	handleNextContentRadioBtns($content) {
		const $radioBtns = $(`${this.selector}__radio-btn`, $content);

		if ($radioBtns.length) {
			$radioBtns.attr('checked', false);
		}
	}

	/**
	 * Only display the back button if not on the first content.
	 *
	 * @param {string} vertex
	 */
	handleBackButtonDisplay(vertex) {
		if (!this.mainVerteces.includes(vertex)) {
			this.backBtn.fadeIn();
		} else {
			this.backBtn.fadeOut();
		}
	}

	handleModal() {
		const modalComponent = this.modalRoot?.get(0)?.component;

		if (!modalComponent) {
			this.modalRoot?.on('modal-init', this.onModalInit.bind(this));
		} else {
			this.onModalInit(null, modalComponent);
		}
	}

	onModalInit(event, modalInstance: typeof Modal) {
		modalInstance.on('modal-close', this.onModalClose.bind(this));
	}

	onModalClose() {
		this.clearContentInputs('form');
	}

	displayPreviousPage() {
		this.answers.pop();
		let $previousContent = null;
		let prevVertex = null;

		if (this.prevVertexStack.length) {
			prevVertex = this.prevVertexStack.pop();
			this.changeContainerBackground(prevVertex);
			this.handleBackButtonDisplay(prevVertex);
			$previousContent = $(this.containers[prevVertex], this.selector);
		}

		const latestVertex = this.currentVertexStack.pop();
		this.computeCurrentPageNumber(); // must be after currentVertexStack.pop as this function is using the currentVertexStack variable

		if ($previousContent !== null && $previousContent.length) {
			$(this.containers[latestVertex], this.selector).fadeOut(400, () => {
				$(this.containers[latestVertex], this.selector).removeClass(
					this.classes
				);
				$previousContent.fadeIn();
			});
		}
	}

	changeContainerBackground(vertex) {
		const modalOverlay = $('#js-fundraiser-quiz .modal-overlay');

		if (vertex === 'form') {
			this.modalContainer.addClass('dev-form');

			modalOverlay.addClass('dev-form');
		} else {
			this.modalContainer.removeClass('dev-form');

			modalOverlay.removeClass('dev-form');
		}
	}

	createGraph() {
		this.verteces.forEach((vertex) => {
			this.addVertex(vertex);
		});

		this.addEdge('donate', 'form');
		this.addEdge('streaming', 'create');
		this.addEdge('personal', 'collect');
		this.addEdge('collect', 'who');
		this.addEdge('who', 'create');
		this.addEdge('social', 'gathering');
		this.addEdge('gathering', 'collect');
	}

	addVertex(v) {
		// set the adjacency list to an empty array.
		this.graph.set(v, []);
	}

	addEdge(source, destination) {
		if (this.graph.get(source).indexOf(destination) === -1) {
			this.graph.get(source).push(destination);
		}
	}
}
