/**
 * Once this is complete, consider dropping out features/cards.js
 */
import { singleBreakpoints } from '../../match-media';
import * as matchMedia from '../../match-media';
import Component from '../../Component';

export default class PostCard extends Component {
	// Defines the element selector which will initialize this component
	static selector = '.card-post';

	// Defines children that needs to be queried as part of this component
	static children = {
		title: '.post-title > a',
		titleWords: ['.post-title > a span'],
		excerpt: '.post-desc > span',
		excerptWords: ['.post-desc > span span'],
	};

	// Will be called upon component instantiation
	afterInit() {
		// Cache each word's original text
		this.children.titleWords.forEach((word) => {
			word.originalText = word.innerText;
		});
		this.children.excerptWords.forEach((word) => {
			word.originalText = word.innerText;
		});

		// Listen to resize changes
		this.handleCardResize();
	}

	handleCardResize() {
		const resizeObserver = new ResizeObserver(this.onCardResize.bind(this));
		resizeObserver.observe(this.element);
	}

	onCardResize() {
		this.truncateTitle();
		this.truncateExcerpt();
	}

	truncateTitle() {
		const titleHeight = this.children.title.parentElement.offsetHeight;
		const titleWidth = this.children.title.offsetWidth;
		const titleWords = this.children.titleWords;

		// Loop through each title word
		for (let span of titleWords) {
			if (
				span.offsetTop + span.offsetHeight / 2 > titleHeight
			) {
				span.classList.add('invisible');

				// Add ellipsis to the previous visible span
				const previousSpan = span.previousElementSibling;
				if (
					previousSpan &&
					!previousSpan.classList.contains('invisible')
				) {
					const willTrim = previousSpan.offsetLeft + previousSpan.offsetWidth >= titleWidth - 30;
					previousSpan.textContent =
						previousSpan.originalText.substr(
							0,
							previousSpan.originalText.length - ( willTrim ? 2 : 0 )
						) + '...';
				}
			} else {
				span.textContent = span.originalText;
				span.classList.remove('invisible');
			}
		}
	}

	truncateExcerpt() {
		if (!this.children.excerpt) {
			return;
		}

		const excerptHeight = this.children.excerpt.parentElement.offsetHeight;
		const exceprtWords = this.children.excerptWords;

		// Loop through each title word
		for (let span of exceprtWords) {
			if (span.offsetTop + span.offsetHeight > excerptHeight) {
				span.classList.add('invisible');

				// Add ellipsis to the previous visible span
				const previousSpan = span.previousElementSibling;
				if (
					previousSpan &&
					!previousSpan.classList.contains('invisible')
				) {
					previousSpan.textContent =
						previousSpan.originalText.substr(
							0,
							previousSpan.originalText.length - 2
						) + '...';
				}
			} else {
				span.textContent = span.originalText;
				span.classList.remove('invisible');
			}
		}
	}

	// Triggers when state is change by calling this.setState()
	componentDidUpdate(changedState) {
		// State change updates
	}
}

// Uncomment this section if this component is intended
// to initialize on DOM load.
PostCard.init();
