import $ from 'jquery';

export default class FundraiserQuiz {

	/**
	 *
	 * @param {object} options Fundraiser Options
	 *
	 * Available options
	 * initialVertex - Initial vertex to show when modal appears
	 * single        - If true, hides pagination and back buttons
	 */
	constructor (options) {
		this.selector = ".fundraiser-quiz";
		this.parentContainer = $(`${this.selector}`);
		this.backBtn = $(`${this.selector}__back-btn`);
		this.choices = $(`${this.selector}__radio-btn`);
		this.modalContainer = $("#js-fundraiser-quiz .modal-container");
		this.form = $(`form${this.selector}`);
		this.paginationContainer = $(`${this.selector}__pagination`);
		this.currentPageContainer = $(`${this.selector}__current-page`);
		this.totalPageContainer = $(`${this.selector}__total-page`);
		this.totalPages = 1;
		this.currentPage = 0;
		this.options = options;

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

		this.mainVerteces = ["donate", "streaming", "personal", "social"];
		this.verteces = ["donate", "streaming", "personal", "social", "form", "create", "collect", "who", "gathering"];
		this.graph = new Map();
		this.prevVertexStack = [];
		this.currentVertexStack = [];
		this.answers = [];

		this.classes = "visible static";

		this.init();
		this.createGraph();

		/**
		 * Initialize the radio button events
		 */
		if (this.choices.length) {
			this.choices.on("click", e => {
				this.displayNextPage(e.target);
			})
		}

		this.backBtn.on("click", () => {
			this.displayPreviousPage();
		});

		this.form.on("submit", e => {
			const [lastVertex] = this.currentVertexStack.slice(-1);

			if (lastVertex !== "form") {
				e.preventDefault();
			}
		});
	}

	init () {
		// Default initial step
		let $initialStepContent = $(`${this.selector}--step-one`);

		this.changeContainerBackground(false);

		// Override iniial step if supplied in options
		if ( this.options.initialVertex ) {
			const $content = $(`[data-vertex="${this.options.initialVertex}"]:not(input)`, this.selector);
			if ( $content.length ) {
				$initialStepContent.hide();
				$initialStepContent = $content;
			}

			this.changeContainerBackground(this.options.initialVertex);
		}

		this.choices.attr("checked", false);
		this.backBtn.hide();
		$(`${this.selector}--steps`).removeClass(this.classes);
		$initialStepContent.fadeIn(500, () => {
			$initialStepContent.addClass(this.classes);
		});
		this.paginationContainer.hide();
	}

	displayNextPage (btn) {
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
		const $content = $(`[data-vertex="${nextVertex}"]:not(input)`, this.selector);

		if ($content.length) {
			this.prevVertexStack.push(vertex);
			this.handleNextContentRadioBtns($content);
			$(this.containers[vertex], this.selector).fadeOut(400, () => {
				this.changeContainerBackground(nextVertex);
				$content.addClass(this.classes);
				$content.fadeIn();
			});
		}
	}

	computeCurrentPageNumber () {
		this.currentPage = 1 + this.currentVertexStack.length;
		this.currentPageContainer.html(this.currentPage);
	}

	computeTotalPageNumber (vertex) {
		let nextVertex = this.graph.get(vertex);

		while (nextVertex.length) {
			const [lastItem] = nextVertex.slice(-1);

			nextVertex = this.graph.get(lastItem);

			++this.totalPages;
		}

		this.totalPageContainer.html(this.totalPages);
	}

	/**
	 * Clears all the radio buttons of the next content
	 * @param {jQuery object} $content
	 */
	handleNextContentRadioBtns ($content) {
		const $radioBtns = $(`${this.selector}__radio-btn`, $content);

		if ($radioBtns.length) {
			$radioBtns.attr("checked", false);
		}
	}

	/**
	 * Only display the back button if not on the first content.
	 * @param {string} vertex
	 */
	handleBackButtonDisplay (vertex) {
		if (! this.mainVerteces.includes(vertex)) {
			this.backBtn.fadeIn();
		} else {
			this.backBtn.fadeOut();
		}
	}

	displayPreviousPage () {
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
				$(this.containers[latestVertex], this.selector).removeClass(this.classes);
				$previousContent.fadeIn();
			});
		}
	}

	changeContainerBackground (vertex) {
		if (vertex === "form") {
			this.modalContainer.addClass("dev-form");
		} else {
			this.modalContainer.removeClass("dev-form");
		}
	}

	createGraph () {
		this.verteces.forEach(vertex => {
			this.addVertex(vertex);
		});

		this.addEdge("donate", "form");
		this.addEdge("streaming", "create");
		this.addEdge("personal", "collect");
		this.addEdge("collect", "who");
		this.addEdge("who", "create");
		this.addEdge("social", "gathering");
		this.addEdge("gathering", "collect");

	}

	addVertex (v) {
		// set the adjacency list to an empty array.
		this.graph.set(v, []);
	}

	addEdge (source, destination) {
		if (this.graph.get(source).indexOf(destination) === -1) {
			this.graph.get(source).push(destination);
		}
	}

}

