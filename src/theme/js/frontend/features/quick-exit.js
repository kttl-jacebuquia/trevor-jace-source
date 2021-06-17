import $ from 'jquery';

const TICK_TIMEOUT = Symbol();
const TICK_COUNTER = Symbol();
const REDIRECT_TO = `http://www.intotime.com/`;

class QuickExit {
	static init() {
		if ( 'ontouchstart' in window ) {
			this.initTapHandler();
		}
		else {
			this.initEscHandler();
		}
	}

	static initEscHandler() {
		$(document).on('keyup', e => {
			if ([e.key, e.code].some(key => /esc/i.test(key)) ) {
				this.onTick();
			}
		});
	}

	static initTapHandler() {
		// Click is simulated by tap on mobile as well
		$(document).on('click', this.onTick.bind(this));
	}

	static onTick() {
		// If a tick is longer than 1 second,
		// it will not be accounted for quick exit
		// and counter will reset
		this[TICK_TIMEOUT] = setTimeout(() => {
			this[TICK_COUNTER] = 0;
		}, 1000);

		if ( ++this[TICK_COUNTER] >= 3) {
			this.goAway();
		}
	}

	static goAway() {
		window.open(REDIRECT_TO, "_newtab");
		window.location.replace("http://google.com");
	}
}

QuickExit[TICK_COUNTER] = 0;

export default QuickExit;
