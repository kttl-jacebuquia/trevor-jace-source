import $ from 'jquery';

class FloatingBlock {
	constructor($elem) {
		this.$elem = $elem;

		this.calc();
	}

	calc() {
		// TODO: Complete implementing
	}
}

export default function floatingBlock($elem) {
	$elem.each((idx, elem) => new FloatingBlock($(elem)));
}
