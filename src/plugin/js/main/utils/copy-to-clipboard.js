// https://stackoverflow.com/a/46118025
export default function copyToClipboard(text) {
	const dummy = document.createElement('input');
	// to avoid breaking orgain page when copying more words
	// cant copy when adding below this code
	// dummy.style.display = 'none'
	document.body.appendChild(dummy);
	//Be careful if you use texarea. setAttribute('value', value), which works with "input" does not work with "textarea". – Eduard
	dummy.value = text.trim();

	// source: https://stackoverflow.com/a/47880284
	const selection = document.getSelection();
	const range = document.createRange();
	range.selectNode(dummy);
	selection.removeAllRanges();
	selection.addRange(range);

	document.execCommand('copy');
	selection.removeAllRanges();
	document.body.removeChild(dummy);
}
