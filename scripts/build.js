'use strict';

const fs = require('fs');

// Do this as the first thing so that any code reading it knows the right env.
process.env.BABEL_ENV = 'production';
process.env.NODE_ENV = 'production';

// Makes the script crash on unhandled rejections instead of silently
// ignoring them. In the future, promise rejections that are not handled will
// terminate the Node.js process with a non-zero exit code.
process.on('unhandledRejection', (err) => {
	throw err;
});

// Ensure environment variables are read.
require('../config/env');

const chalk = require('react-dev-utils/chalk');
const webpack = require('webpack');
const configFactory = require('../config/webpack.config');
const formatWebpackMessages = require('react-dev-utils/formatWebpackMessages');

console.log('Creating an optimized production build...');

// Generate configuration
const config = configFactory('production');
const compiler = webpack(config);

return new Promise((resolve, reject) => {
	compiler.run((err, stats) => {
		let messages;
		if (err) {
			if (!err.message) {
				return reject(err);
			}

			let errMessage = err.message;

			// Add additional information for postcss errors
			if (Object.prototype.hasOwnProperty.call(err, 'postcssNode')) {
				errMessage +=
					'\nCompileError: Begins at CSS selector ' +
					err['postcssNode'].selector;
			}

			messages = formatWebpackMessages({
				errors: [errMessage],
				warnings: [],
			});
		} else {
			messages = formatWebpackMessages(
				stats.toJson({ all: false, warnings: true, errors: true })
			);
		}
		if (messages.errors.length) {
			// Only keep the first error. Others are often indicative
			// of the same problem, but confuse the reader with noise.
			if (messages.errors.length > 1) {
				messages.errors.length = 1;
			}

			console.log('messages.errors', messages.errors);

			return reject(new Error(messages.errors.join('\n\n')));
		}
		if (
			process.env.CI &&
			(typeof process.env.CI !== 'string' ||
				process.env.CI.toLowerCase() !== 'false') &&
			messages.warnings.length
		) {
			console.log(
				chalk.yellow(
					'\nTreating warnings as errors because process.env.CI = true.\n' +
						'Most CI servers set it automatically.\n'
				)
			);
			return reject(new Error(messages.warnings.join('\n\n')));
		}

		fs.copyFileSync(
			'src/theme/css/foundation/foundation.css',
			'theme/static/css/foundation.css'
		);

		return resolve({
			stats,
			warnings: messages.warnings,
		});
	});
});
