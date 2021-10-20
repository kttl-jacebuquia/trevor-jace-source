import $ from 'jquery';

const TICK_TIMEOUT = Symbol();
const TICK_COUNTER = Symbol();
const TOUCHSTART_TIMEOUT = Symbol();
const REDIRECT_TO = `http://www.intotime.com/`;

class QuickExit {
	static [TICK_TIMEOUT]: number = 0;
	static [TICK_COUNTER]: number = 0;
	// Allows us to determine if consecutive touchstart intent is "multi-tap"
	static [TOUCHSTART_TIMEOUT]: number = 0;
	static touchStarted = false;

	static init() {
		if ('ontouchstart' in window) {
			this.initTapHandler();
		} else {
			this.initEscHandler();
		}
	}

	static initEscHandler() {
		$(document).on('keyup', (e: KeyboardEvent) => {
			if ([e.key, e.code].some((key) => /esc/i.test(key))) {
				this.onTick();
			}
		});
	}

	static initTapHandler() {
		window.document.addEventListener(
			'touchstart',
			this.onTouchStart.bind(this)
		);
	}

	static onTouchStart(e: TouchEvent) {
		// Ensure single finger tap
		if (e.touches.length > 1) {
			this[TICK_COUNTER] = 0;
			return;
		}

		window.clearTimeout(this[TOUCHSTART_TIMEOUT]);

		// Consecutive touches should only have at most 300ms intervals
		// For us to determine a multi-tap intent
		this[TOUCHSTART_TIMEOUT] = window.setTimeout(() => {
			this[TICK_COUNTER] = 0;
		}, 500);

		if (++this[TICK_COUNTER] > 1) {
			if (this[TICK_COUNTER] >= 3) {
				this.goAway();
			}

			// Prevents default browser behaviors,
			// such as double-tap zoom
			return false;
		}
	}

	static onTick() {
		// If a tick is longer than 1 second,
		// it will not be accounted for quick exit
		// and counter will reset
		this[TICK_TIMEOUT] = window.setTimeout(() => {
			this[TICK_COUNTER] = 0;
		}, 1000);

		if (++this[TICK_COUNTER] >= 3) {
			this.goAway();
		}
	}

	static goAway() {
		window.open(REDIRECT_TO, '_newtab');
		window.location.replace('http://google.com');
	}
}

QuickExit[TICK_COUNTER] = 0;

export default QuickExit;
