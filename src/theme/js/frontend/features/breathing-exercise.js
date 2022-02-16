import DrawBlob, { generatePoints } from 'blob-animated';
import $ from 'jquery';
import * as focusTrap from 'focus-trap';

import { toggleBodyFix, onEscapePress } from './global-ui';

const animateElement = (element, properties, duration, delay = 0) =>
	new Promise((resolve) => {
		$(element).delay(delay).animate(properties, duration, '', resolve);
	});

class BreathingExercise {
	static toggleSelector = '.js-breathing-exercise-toggle';
	static overlaySelector = '.breathing-exercise-overlay';
	static wordsArray = [
		`Let's Begin`,
		'Breathe in through the nose',
		'And out through the mouth',
		'In through the nose',
		'Out through the mouth',
		'Breathe in',
		'Breathe out',
		'In',
		'Out',
	];

	countDownActive = false; // Determines if countdown should continue or is skipped
	breathingActive = false;

	static init() {
		const $toggles = $(this.toggleSelector);

		if ($toggles.length) {
			this.initializeOverlay();
			this.initializeFocusTrap();

			$toggles.on('click', (e) => {
				e.preventDefault();
				this.showOverlay();
			});
		}
	}

	static initializeOverlay() {
		// Elements
		this.$overlay = $(this.overlaySelector);
		this.overlay = this.$overlay.get(0);
		this.container = this.overlay.querySelector(
			'.breathing-exercise-overlay__container'
		);
		this.close = this.overlay.querySelector(
			'.breathing-exercise-overlay__close'
		);
		this.start = this.overlay.querySelectorAll('.start');
		this.stepOne = this.overlay.querySelector('.step-one');
		// Countdown
		this.countdown = this.overlay.querySelector('.countdown');
		this.countdownNumber = this.overlay.querySelector('.countdown__number');
		this.countdownSkip = this.overlay.querySelector('.countdown__skip');
		// Breathing
		this.breathing = this.overlay.querySelector('.breathing');
		this.copyWrapper = this.overlay.querySelector('.copy-wrapper');
		this.rotateCopy = this.overlay.querySelector('.rotate-copy');
		// Breathing End
		this.breathingEnd = this.overlay.querySelector('.breathing-end');
		this.repeatCTA = this.overlay.querySelectorAll('.repeat');

		// Blob
		this.blobCanvas = this.overlay.querySelector('#blob');
		this.ctx = this.blobCanvas.getContext('2d');
		this.Blob = new DrawBlob({
			vectors: generatePoints({ sides: 7 }),
			canvas: this.blobCanvas,
			speed: 120,
			scramble: 0.1,
			colorFunction: this.colorFunction,
		});

		$(this.start).on('click', () => this.onStart());
		$(this.close).on('click', () => this.hideOverlay());
		$(this.countdownSkip).on('click', () => this.skipCountdown());
		$(this.repeatCTA).on('click', () => this.restart());
		this.$overlay.on('transitionend', (e) =>
			this.onOverlayTransitionEnd(e)
		);

		onEscapePress(this.hideOverlay.bind(this));
	}

	static initializeFocusTrap() {
		// Create focus trap
		this.focustTrap = focusTrap.createFocusTrap(this.overlay, {
			initialFocus: this.overlay,
		});
	}

	static showOverlay() {
		toggleBodyFix(true);
		this.$overlay.addClass('show');
		this.startCountdown();
	}

	static hideOverlay() {
		this.countDownActive = false;
		this.breathingActive = false;

		toggleBodyFix(false);
		this.$overlay.removeClass('show');
		this.focustTrap?.deactivate();
	}

	static onOverlayTransitionEnd(e) {
		if (e.target === e.currentTarget) {
			if (this.$overlay.hasClass('show')) {
				this.onAfterShow();
			} else {
				this.onAfterHide();
			}
		}
	}

	static onAfterShow() {
		this.focustTrap?.activate();
	}

	static onAfterHide() {
		$(this.coundown).hide();
		$(this.breathing).hide();
		$(this.breathingEnd).hide();
	}

	// @returns Promise
	static startCountdown() {
		this.countDownActive = true;

		$(this.countdown).fadeIn();

		new Promise((resolve) => {
			const runAnimation = (currentCount) => {
				$(this.countdownNumber).text(currentCount);
				animateElement(this.countdownNumber, { opacity: 1 }, 500)
					.then(() =>
						animateElement(
							this.countdownNumber,
							{ opacity: 0 },
							500,
							1000
						)
					)
					.then(() => {
						if (!this.countDownActive) {
							return;
						}

						--currentCount;

						if (currentCount) {
							runAnimation(currentCount);
							return;
						}

						$(this.countdown).fadeOut(() => {
							this.showStepOne();
							resolve();
						});
					});
			};

			runAnimation(5);
		});
	}

	static skipCountdown() {
		this.countDownActive = false;
		$(this.countdown).fadeOut(() => {
			this.showStepOne();
		});
	}

	static showStepOne() {
		$(this.stepOne).fadeIn({
			complete: () => $(this.stepOne).focus(),
		});
	}

	static colorFunction(ctx) {
		const gradient = ctx.createRadialGradient(500, 600, 30, 600, 600, 500);
		gradient.addColorStop(0, '#eeedfc');
		gradient.addColorStop(1, '#bab8e8');
		return gradient;
	}

	static rotateWords() {
		let count = 0;

		this.breathingActive = true;

		$(this.rotateCopy).text(this.wordsArray[0]);

		const runWords = setInterval(() => {
			count++;

			if (!this.breathingActive) {
				clearInterval(runWords);
				return;
			}

			if (count % this.wordsArray.length == 1) {
				$(this.blobCanvas).addClass('scale');
			}

			if (count % this.wordsArray.length == 0) {
				clearInterval(runWords);

				setTimeout(() => {
					$(this.copyWrapper).css('opacity', '0');
				}, 1000);

				setTimeout(() => {
					$(this.rotateCopy).text(
						'You might be feeling more calm, relaxed, focused'
					);
					$(this.copyWrapper).css('opacity', '1');
				}, 3000);

				setTimeout(() => {
					this.showBreathingEnd();
				}, 6000);

				setTimeout(() => {
					$(this.blobCanvas).addClass('scale-half');
				}, 5000);
			} else {
				$(this.rotateCopy).fadeOut(400, () => {
					$(this.rotateCopy)
						.text(this.wordsArray[count % this.wordsArray.length])
						.fadeIn(400);
				});
			}
		}, 5000);
	}

	static showBreathingEnd() {
		this.breathingActive = false;
		$(this.breathing).fadeOut('slow', () => {
			$(this.breathingEnd).fadeIn({
				duration: 'slow',
				complete: () => $(this.breathingEnd).focus(),
			});
		});
	}

	static onStart() {
		$(this.stepOne).fadeOut('slow');
		setTimeout(() => {
			$(this.breathing).fadeIn({
				duration: 1500,
				complete: () => $(this.breathing).focus(),
			});
			this.rotateWords();
		}, 700);
	}

	static restart() {
		$(this.breathingEnd).fadeOut(() => {
			$(this.blobCanvas).removeClass('scale-half');

			this.startCountdown();
		});
	}
}

export default BreathingExercise;
