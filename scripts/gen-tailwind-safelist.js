const fs = require('fs');
const paths = require('../config/paths');
const resolveConfig = require('tailwindcss/resolveConfig');
const { theme } = resolveConfig(require(paths.tailwindCssConf));

const options = {};

function optionWalker(root, prefix = '', walk = 2) {
	const out = {};
	Object.keys(root).forEach((key) => {
		const val = root[key];
		if ('object' === typeof val && walk > 0) {
			return Object.assign(
				out,
				optionWalker(
					val,
					prefix + (prefix && key ? '-' : '') + key,
					walk - 1
				)
			);
		}

		if ('DEFAULT' === key) {
			key = '';
		}

		key = prefix + (prefix && key ? '-' : '') + key;

		out[key] =
			key +
			': ' +
			(/boolean|number|string/.test(typeof val)
				? val
				: JSON.stringify(val));
	});

	return out;
}

const m = optionWalker(theme.margin);
const p = optionWalker(theme.padding);

['', 'x', 'y', 't', 'b', 'l', 'r'].forEach((iter) => {
	options['m' + iter] = m;
	options['p' + iter] = p;
});

options['bg'] = optionWalker(theme.backgroundColor);
options['text'] = Object.assign(
	optionWalker(theme.textColor),
	optionWalker(theme.fontSize)
);
options['font'] = Object.assign(
	optionWalker(theme.fontWeight),
	optionWalker(theme.fontFamily, '', 0)
);
options['leading'] = optionWalker(theme.lineHeight);
options['tracking'] = optionWalker(theme.letterSpacing);
options['w'] = optionWalker(theme.width);
options['h'] = optionWalker(theme.height);
options['rounded'] = optionWalker(theme.borderRadius);
options['max-w'] = optionWalker(theme.maxWidth);
options['container'] = { '': '' };
options['border'] = Object.assign(
	optionWalker(theme.borderColor),
	optionWalker(theme.borderWidth)
);

const fileContent = [];

Object.keys(options).forEach((rule) => {
	const val = options[rule];
	['', 'md', 'xl'].forEach((screen) =>
		Object.keys(val).forEach((entry) =>
			fileContent.push(`${screen + (screen && ':')}${rule}-${entry}`)
		)
	);
});

fs.writeFileSync(paths.tailwindSafeListOut, fileContent.join('\r\n'));
