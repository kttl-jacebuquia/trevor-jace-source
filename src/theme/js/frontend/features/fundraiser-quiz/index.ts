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
	modalContent?: JQuery;
	content?: JQuery;
	form?: JQuery;
	paginationContainer?: JQuery;
	currentPageContainer?: JQuery;
	totalPageContainer?: JQuery;
	$devInquirySlide?: JQuery;
	controlsWrapper?: JQuery;
	controlsReferencePrimary?: JQuery;
	controlsReferenceSecondary?: JQuery;

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
		this.modalContent = $('#js-fundraiser-quiz .modal-content-wrap');
		this.content = this.modalRoot?.find('.fundraiser-quiz__contents');
		this.form = $(`form${this.selector}`);
		this.controlsWrapper = this.modalRoot?.find(
			'.fundraiser-quiz__controls'
		);
		this.controlsReferencePrimary = this.modalRoot?.find(
			'.fundraiser-quiz__controls-reference-primary'
		);
		this.controlsReferenceSecondary = this.modalRoot?.find(
			'.fundraiser-quiz__controls-reference-secondary'
		);
		this.paginationContainer = $(`${this.selector}__pagination`);
		this.currentPageContainer = $(`${this.selector}__current-page`);
		this.totalPageContainer = $(`${this.selector}__total-page`);
		this.totalPages = 1;
		this.currentPage = 0;
		this.options = options;

		// this.modalRoot?.attr('tabindex', '0');
		this.modalContainer?.removeAttr('tabindex');

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

		// Handle focus
		this.handleFocusableChildren();
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
		this.controls?.hide();

		$initialStepContent.attr('tabindex', '0').fadeIn(500, () => {
			setTimeout(() => {
				$initialStepContent.addClass(this.classes);
				$initialStepContent?.get(0)?.focus();
			}, 100);
		});

		this.controlsWrapper?.hide();
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
		submitButton?.removeAttribute('aria-disabled');
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
		choicesSubmitBtn?.removeAttribute('aria-disabled');
	}

	displayNextPage(btn?: HTMLInputElement) {
		const vertex = btn?.dataset.vertex || '';
		const [nextVertex] = this.graph.get(vertex);
		this.answers?.push(btn?.value || '');
		this.currentVertexStack?.push(nextVertex);
		this.handleBackButtonDisplay(nextVertex);

		if (this.mainVerteces.includes(vertex)) {
			this.totalPages = 1; // reset the number of pages.
			this.computeTotalPageNumber(vertex);
		}

		this.computeCurrentPageNumber();

		// Get the new content
		const $content = $(
			`[data-vertex="${nextVertex}"]:not(input)`,
			this.selector
		);

		if ($content.length) {
			const $oldContent = $(this.containers[vertex], this.selector);
			this.prevVertexStack?.push(vertex);
			this.handleNextContentRadioBtns($content);

			this.clearContentInputs(nextVertex);

			this.controlsWrapper?.fadeOut(200, () => {
				this.controlsWrapper?.fadeIn(200);
			});

			// Hide old content
			$oldContent.removeAttr('tabindex').fadeOut(400, () => {
				this.changeContainerBackground(nextVertex);
				$content.addClass(this.classes);
			});

			// Show new content
			$content
				.attr('tabindex', '0')
				.removeClass('hidden')
				.delay(400)
				.fadeIn(400);

			setTimeout(() => {
				$content.get(0)?.focus();
			}, 900);
		}
	}

	displayPreviousPage() {
		this.answers?.pop();
		let $previousContent: JQuery | null = null;
		let prevVertex = null;

		if (this.prevVertexStack?.length) {
			prevVertex = this.prevVertexStack.pop();
			this.changeContainerBackground(prevVertex);
			this.handleBackButtonDisplay(prevVertex);
			$previousContent = $(
				this.containers[prevVertex || 0],
				this.selector
			);
		} else {
			this.controlsWrapper?.hide();
		}

		const latestVertex = this.currentVertexStack?.pop() || 0;
		const $currentContent = this.modalRoot?.find(
			this.containers[latestVertex]
		);

		this.computeCurrentPageNumber(); // must be after currentVertexStack.pop as this function is using the currentVertexStack variable

		if ($previousContent?.length) {
			$currentContent?.fadeOut(400, () => {
				$currentContent
					?.removeClass(this.classes)
					.removeAttr('tabindex');
			});

			$previousContent.attr('tabindex', '0').delay(400).fadeIn(400);
		}

		setTimeout(() => {
			$previousContent?.get(0).focus();
		}, 1000);
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
					input.setAttribute('aria-disabled', 'true');
					break;
			}
		});

		// Reset next page formss
		$content.find('form')?.get(0)?.reset();
	}

	computeCurrentPageNumber() {
		this.currentPage = 1 + this.currentVertexStack.length;
		this.currentPageContainer?.html(String(this.currentPage));

		this.paginationContainer?.attr(
			'aria-label',
			`slide ${this.currentPage} of ${this.totalPages}`
		);

		this.paginationContainer?.html(
			`${this.currentPage}/${this.totalPages}`
		);

		this.backBtn?.attr('data-current', this.currentPage);
	}

	computeTotalPageNumber(vertex) {
		let nextVertex = this.graph.get(vertex);

		while (nextVertex.length) {
			const [lastItem] = nextVertex.slice(-1);

			nextVertex = this.graph.get(lastItem);

			++this.totalPages;
		}
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
		const modalComponent: Modal = this.modalContainer?.get(0)?.component;

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

	handleFocusableChildren() {
		const $focusableChildren = this.modalContainer?.find('button, input');

		$focusableChildren?.toArray().forEach((child) => {
			child.addEventListener('focus', () => {
				this.checkElementVisibility(child);
			});
		});
	}

	checkElementVisibility(child: HTMLElement) {
		const { top } = child.getBoundingClientRect();
		const viewportHeight = window.innerHeight - 80; // Account for iOS Safari controls

		// If element is out of view, scroll it into view
		if (top > viewportHeight || top < 0) {
			const halfViewport = viewportHeight / 2;
			const targetScroll =
				(this.modalContainer?.get(0)?.scrollTop || 0) +
				top -
				halfViewport;

			this.modalContainer?.get(0)?.scrollTo(0, targetScroll);
		}
	}
}
