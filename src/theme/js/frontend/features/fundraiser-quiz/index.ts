import $ from 'jquery';
import FloatingLabelInput from '../floating-label-input';
import phoneFormat from '../contact-form';
import { WPAjax } from '../wp-ajax';

interface FundraiserQuizOptions {
	[key: string]: any;
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
	modalContainer?: JQuery;
	form?: JQuery;
	paginationContainer?: JQuery;
	currentPageContainer?: JQuery;
	totalPageContainer?: JQuery;
	$devInquirySlide?: JQuery;

	// HTMLElements
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
		this.modalContainer = $('#js-fundraiser-quiz .modal-container');
		this.form = $(`form${this.selector}`);
		this.paginationContainer = $(`${this.selector}__pagination`);
		this.currentPageContainer = $(`${this.selector}__current-page`);
		this.totalPageContainer = $(`${this.selector}__total-page`);
		this.totalPages = 1;
		this.currentPage = 0;
		this.options = options;

		this.ajaxAction = this.parentContainer.data('ajax-action');
		this.$devInquirySlide = this.parentContainer.find(
			'[data-vertex="form"]'
		);
		this.devInquiryForm = this.$devInquirySlide.find('form').get(0);
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
		if (this.choices.length) {
			this.choices.on('click' as any, (e) => {
				this.displayNextPage(e.target);
			});
		}

		this.backBtn.on('click', () => {
			this.displayPreviousPage();
		});

		this.form.on('submit' as any, (e) => {
			const [lastVertex] = this.currentVertexStack?.slice(-1);

			if (lastVertex !== 'form') {
				e.preventDefault();
			}
		});

		this.initDevInquiryForm();
	}

	show(options = {}) {
		// Default initial step
		let $initialStepContent = $(`${this.selector}--step-one`);

		// Clear inputs
		this.clearContentInputs(`${this.selector}--step-one`);

		this.changeContainerBackground(false);

		// Reset counter
		this.currentVertexStack = [];

		// Override iniial step if supplied in options
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

		this.choices.attr('checked', false);
		this.backBtn.hide();
		$(`${this.selector}--steps`).removeClass(this.classes);
		this.paginationContainer.hide();

		setTimeout(() => {
			$initialStepContent.fadeIn(500, () => {
				$initialStepContent.addClass(this.classes);
			});
		}, 100);
	}

	// Incorporate FloatingLabelInputs to FormAssembly DevInquiryForm
	initDevInquiryForm() {
		const embeddedForm = this.devInquiryForm;

		if (embeddedForm) {
			const [...inputFields] = embeddedForm.querySelectorAll('.oneField');

			embeddedForm.addEventListener('reset', () =>
				this.onDevInquiryReset(embeddedForm)
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

				// Determine if field has to be activated by default
				switch (inputField.id) {
					case 'tfa_1885-D':
					case 'tfa_1907-D':
					case 'tfa_1938-D':
						options.activated = true;
						break;
				}

				FloatingLabelInput.initializeWithElement(inputField, options);
			});

			embeddedForm.addEventListener(
				'submit',
				this.onDevInquirySubmit.bind(this)
			);
		}
	}

	onDevInquiryReset(form: HTMLFormElement) {
		const inputFields: HTMLElement[] = $(form).find('.oneField').toArray();

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
			const errorFields = this.devInquiryForm.querySelectorAll('.errFld');

			// Don't submit if there's any error
			if (errorFields.length) {
				return;
			}

			const formData = [
				...new window.FormData(this.devInquiryForm).entries(),
			].reduce((all, [key, value]) => {
				all[key] = value;
				return all;
			}, {});

			const response = await WPAjax({
				action: this.ajaxAction,
				data: formData,
			});

			if (/success/i.test(response?.status || '')) {
				this.onFormSubmitSuccess();
			}
		}, 100);
	}

	onFormSubmitSuccess() {
		this.devInquiryFieldsContainer.outerHTML = `<p>Thank you for your submission!</p>`;
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
			$content.removeClass('hidden').fadeIn();
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
