'use strict';

const path = require('path');
const webpack = require('webpack');
const PnpWebpackPlugin = require('pnp-webpack-plugin');
const CaseSensitivePathsPlugin = require('case-sensitive-paths-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const safePostCssParser = require('postcss-safe-parser');
const WatchMissingNodeModulesPlugin = require('react-dev-utils/WatchMissingNodeModulesPlugin');
const getCSSModuleLocalIdent = require('react-dev-utils/getCSSModuleLocalIdent');
const paths = require('./paths');
const getClientEnvironment = require('./env');
const ModuleNotFoundPlugin = require('react-dev-utils/ModuleNotFoundPlugin');

const appPackageJson = require(paths.appPackageJson);

// Source maps are resource heavy and can cause out of memory issue for large source files.
const shouldUseSourceMap = process.env.GENERATE_SOURCEMAP !== 'false';


// style files regexes
const cssRegex = /\.css$/;
const cssModuleRegex = /\.module\.css$/;
const sassRegex = /\.(scss|sass)$/;
const sassModuleRegex = /\.module\.(scss|sass)$/;

// This is the production and development configuration.
// It is focused on developer experience, fast rebuilds, and a minimal bundle.
module.exports = function (webpackEnv) {
	const isEnvDevelopment = webpackEnv === 'development';
	const isEnvProduction = webpackEnv === 'production';

	// We will provide `paths.publicUrlOrPath` to our app
	// as %PUBLIC_URL% in `index.html` and `process.env.PUBLIC_URL` in JavaScript.
	// Omit trailing slash as %PUBLIC_URL%/xyz looks better than %PUBLIC_URL%xyz.
	// Get environment variables to inject into our app.
	const env = getClientEnvironment('/');

	// common function to get style loaders
	const getStyleLoaders = (cssOptions, preProcessor) => {
		const loaders = [
			isEnvDevelopment && require.resolve('style-loader'),
			isEnvProduction && {
				loader: MiniCssExtractPlugin.loader,
				options: {},
			},
			{
				loader: require.resolve('css-loader'),
				options: {...cssOptions, url: false},
			},
			{
				// Options for PostCSS as we reference these options twice
				// Adds vendor prefixing based on your specified browser support in
				// package.json
				loader: require.resolve('postcss-loader'),
				options: {
					postcssOptions: {
						// Necessary for external CSS imports to work
						// https://github.com/facebook/create-react-app/issues/2677
						ident: 'postcss',
						plugins: [
							require('@tailwindcss/jit')(paths.tailwindCssConf),
							require('postcss-flexbugs-fixes'),
							require('postcss-preset-env')({
								autoprefixer: {
									flexbox: 'no-2009',
								},
								stage: 3,
							}),
						],
						sourceMap: isEnvProduction && shouldUseSourceMap,
					},
				}
			},
		].filter(Boolean);
		if (preProcessor) {
			loaders.push(
				// {
				// 	loader: require.resolve('resolve-url-loader'),
				// 	options: {
				// 		sourceMap: isEnvProduction && shouldUseSourceMap,
				// 	},
				// },
				{
					loader: require.resolve(preProcessor),
					options: {
						sourceMap: true,
						prependData: preProcessor === 'sass-loader'
							? ('$env: ' + process.env.NODE_ENV + ';')
							: undefined,
					},
				}
			);
		}
		return loaders;
	};

	const entry = {
		// - Plugin
		'plugin/js/main': paths.pluginJSMain,
		'plugin/js/blocks': paths.pluginJSBlocks,
		// - Theme
		'theme/js/admin': paths.themeJSAdmin,
		'theme/js/frontend': paths.themeJSFrontEnd,
		'theme/js/site-banners': paths.themeJSFSiteBanners,
	};

	if (isEnvDevelopment) {
		// - Hot Loader
		entry.webpackHot = require.resolve('react-dev-utils/webpackHotDevClient');
		// - Plugin
		entry['plugin/css/main'] = paths.pluginCSSDevMain;
		// - Theme
		entry['theme/css/admin'] = paths.themeCSSDevAdmin;
		entry['theme/css/frontend'] = paths.themeCSSDevFrontend;
	} else {
		// - Plugin
		entry['plugin/css/main'] = paths.pluginCSSMain;
		// - Theme
		entry['theme/css/admin'] = paths.themeCSSAdmin;
		entry['theme/css/frontend'] = paths.themeCSSFrontend;
	}

	return {
		mode: isEnvProduction ? 'production' : isEnvDevelopment && 'development',
		// Stop compilation early in production
		bail: isEnvProduction,
		devtool: isEnvProduction
			? 'nosources-source-map' // without source content
			: isEnvDevelopment && 'cheap-module-source-map',
		// These are the "entry points" to our application.
		// This means they will be the "root" imports that are included in JS bundle.
		entry,
		output: {
			// The build folder.
			path: isEnvProduction ? paths.build : undefined,
			// Add /* filename */ comments to generated require()s in the output.
			pathinfo: isEnvDevelopment,
			futureEmitAssets: true,
			publicPath: 'http://trevor-node.lndo.site/',
			// webpack uses `publicPath` to determine where the app is being served from.
			// It requires a trailing slash, or the file assets will get an incorrect path.
			// We inferred the "public path" (such as / or /my-project) from homepage.
			// Point sourcemap entries to original disk location (format as URL on Windows)
			// devtoolModuleFilenameTemplate: isEnvProduction
			// 	? info =>
			// 		path
			// 			.relative(paths.src, info.absoluteResourcePath)
			// 			.replace(/\\/g, '/')
			// 	: isEnvDevelopment &&
			// 	(info => path.resolve(info.absoluteResourcePath).replace(/\\/g, '/')),
			// Prevents conflicts when multiple webpack runtimes (from different apps)
			// are used on the same page.
			jsonpFunction: `webpackJsonp${appPackageJson.name}`,
			// this defaults to 'window', but by setting it to 'this' then
			// module chunks which are built will work in web workers as well.
			globalObject: 'this',
		},
		optimization: {
			minimize: isEnvProduction,
			minimizer: [
				// This is only used in production mode
				new TerserPlugin({
					terserOptions: {
						parse: {
							// We want terser to parse ecma 8 code. However, we don't want it
							// to apply any minification steps that turns valid ecma 5 code
							// into invalid ecma 5 code. This is why the 'compress' and 'output'
							// sections only apply transformations that are ecma 5 safe
							// https://github.com/facebook/create-react-app/pull/4234
							ecma: 8,
						},
						compress: {
							ecma: 5,
							warnings: false,
							// Disabled because of an issue with Uglify breaking seemingly valid code:
							// https://github.com/facebook/create-react-app/issues/2376
							// Pending further investigation:
							// https://github.com/mishoo/UglifyJS2/issues/2011
							comparisons: false,
							// Disabled because of an issue with Terser breaking valid code:
							// https://github.com/facebook/create-react-app/issues/5250
							// Pending further investigation:
							// https://github.com/terser-js/terser/issues/120
							inline: 2,
						},
						mangle: {
							safari10: true,
						},
						// Added for profiling in devtools
						keep_classnames: false,
						keep_fnames: false,
						output: {
							ecma: 5,
							comments: false,
							// Turned on because emoji and regex is not minified properly using default
							// https://github.com/facebook/create-react-app/issues/2488
							ascii_only: true,
						},
					},
					sourceMap: shouldUseSourceMap,
				}),
				// This is only used in production mode
				new OptimizeCSSAssetsPlugin({
					cssProcessorOptions: {
						parser: safePostCssParser,
						map: shouldUseSourceMap
							? {
								// `inline: false` forces the sourcemap to be output into a
								// separate file
								inline: false,
								// `annotation: true` appends the sourceMappingURL to the end of
								// the css file, helping the browser find the sourcemap
								annotation: true,
							}
							: false,
					},
					cssProcessorPluginOptions: {
						preset: ['default', {minifyFontValues: {removeQuotes: false}}],
					},
				}),
			],
			// Automatically split vendor and commons
			// https://twitter.com/wSokra/status/969633336732905474
			// https://medium.com/webpack/webpack-4-code-splitting-chunk-graph-and-the-splitchunks-optimization-be739a861366
			// splitChunks: {
			// 	chunks: 'all',
			// 	name: false,
			// },
			// Keep the runtime chunk separated to enable long term caching
			// https://twitter.com/wSokra/status/969679223278505985
			// https://github.com/facebook/create-react-app/issues/5358
			runtimeChunk: {
				name: 'runtime'
			},
		},
		externals: {
			jquery: 'jQuery',
			"@wordpress/blocks": "wp.blocks",
			"@wordpress/block-editor": "wp.blockEditor",
			"@wordpress/components": "wp.components",
			"@wordpress/api-fetch": "wp.apiFetch",
			"@wordpress/hooks": "wp.hooks",
			"@wordpress/element": "wp.element",
			"@wordpress/edit-post": "wp.editPost",
			"@wordpress/plugins": "wp.plugins",
			"@wordpress/compose": "wp.compose",
			"@wordpress/data": "wp.data",
			"lodash": "lodash",
		},
		resolve: {
			mainFiles: ['index'],
			alias: {
				'assets': path.join(paths.src, 'assets'),
				'plugin': path.join(paths.src, 'plugin'),
				'theme': path.join(paths.src, 'theme'),
				'config$': path.join(paths.src, 'config'),
				...(isEnvDevelopment ? {
					// Dev aliases
					'react-dom': '@hot-loader/react-dom',
				} : {})
			},
			plugins: [
				// Adds support for installing with Plug'n'Play, leading to faster installs and adding
				// guards against forgotten dependencies and such.
				PnpWebpackPlugin
			],
		},
		resolveLoader: {
			plugins: [
				// Also related to Plug'n'Play, but this time it tells webpack to load its loaders
				// from the current package.
				PnpWebpackPlugin.moduleLoader(module),
			],
		},
		module: {
			strictExportPresence: true,
			rules: [
				// Disable require.ensure as it's not a standard language feature.
				{parser: {requireEnsure: false}},

				// First, run the linter.
				// It's important to do this before Babel processes the JS.
				false && {
					test: /\.(js|mjs|jsx|ts|tsx)$/,
					enforce: 'pre',
					use: [
						{
							options: {
								cache: true,
								formatter: require.resolve('react-dev-utils/eslintFormatter'),
								eslintPath: require.resolve('eslint'),
								resolvePluginsRelativeTo: __dirname,

							},
							loader: require.resolve('eslint-loader'),
						},
					],
					include: paths.src,
				},
				{
					// "oneOf" will traverse all following loaders until one will
					// match the requirements. When no loader matches it will fall
					// back to the "file" loader at the end of the loader list.
					oneOf: [
						// Process application JS with Babel.
						// The preset includes JSX, Flow, TypeScript, and some ESnext features.
						{
							test: /\.(js|mjs|jsx|ts|tsx)$/,
							include: paths.src,
							loader: require.resolve('babel-loader'),
							options: {
								customize: require.resolve(
									'babel-preset-react-app/webpack-overrides'
								),

								plugins: [
									"react-hot-loader/babel",
									"@babel/plugin-proposal-export-default-from",
									[
										require.resolve('babel-plugin-named-asset-import'),
										{
											loaderMap: {
												svg: {
													ReactComponent:
														'@svgr/webpack?-svgo,+titleProp,+ref![path]',
												},
											},
										},
									],
								],
								// This is a feature of `babel-loader` for webpack (not Babel itself).
								// It enables caching results in ./node_modules/.cache/babel-loader/
								// directory for faster rebuilds.
								cacheDirectory: true,
								// See #6846 for context on why cacheCompression is disabled
								cacheCompression: false,
								compact: isEnvProduction,
							},
						},
						// Process any JS outside of the app with Babel.
						// Unlike the application JS, we only compile the standard ES features.
						{
							test: /\.(js|mjs)$/,
							exclude: /@babel(?:\/|\\{1,2})runtime/,
							loader: require.resolve('babel-loader'),
							options: {
								babelrc: false,
								configFile: false,
								compact: false,
								presets: [
									[
										require.resolve('babel-preset-react-app/dependencies'),
										{helpers: true},
									],
								],
								cacheDirectory: true,
								// See #6846 for context on why cacheCompression is disabled
								cacheCompression: false,

								// Babel sourcemaps are needed for debugging into node_modules
								// code.  Without the options below, debuggers like VSCode
								// show incorrect code and set breakpoints on the wrong lines.
								sourceMaps: shouldUseSourceMap,
								inputSourceMap: shouldUseSourceMap,
							},
						},
						// "postcss" loader applies autoprefixer to our CSS.
						// "css" loader resolves paths in CSS and adds assets as dependencies.
						// "style" loader turns CSS into JS modules that inject <style> tags.
						// In production, we use MiniCSSExtractPlugin to extract that CSS
						// to a file, but in development "style" loader enables hot editing
						// of CSS.
						// By default we support CSS Modules with the extension .module.css
						{
							test: cssRegex,
							exclude: cssModuleRegex,
							use: getStyleLoaders({
								importLoaders: 1,
								sourceMap: isEnvProduction && shouldUseSourceMap,
							}),
							// Don't consider CSS imports dead code even if the
							// containing package claims to have no side effects.
							// Remove this when webpack adds a warning or an error for this.
							// See https://github.com/webpack/webpack/issues/6571
							sideEffects: true,
						},
						// Adds support for CSS Modules (https://github.com/css-modules/css-modules)
						// using the extension .module.css
						{
							test: cssModuleRegex,
							use: getStyleLoaders({
								importLoaders: 1,
								sourceMap: isEnvProduction && shouldUseSourceMap,
								modules: {
									getLocalIdent: getCSSModuleLocalIdent,
								},
							}),
						},
						// Opt-in support for SASS (using .scss or .sass extensions).
						// By default we support SASS Modules with the
						// extensions .module.scss or .module.sass
						{
							test: sassRegex,
							exclude: sassModuleRegex,
							use: getStyleLoaders(
								{
									importLoaders: 3,
									sourceMap: isEnvProduction && shouldUseSourceMap,
								},
								'sass-loader'
							),
							// Don't consider CSS imports dead code even if the
							// containing package claims to have no side effects.
							// Remove this when webpack adds a warning or an error for this.
							// See https://github.com/webpack/webpack/issues/6571
							sideEffects: true,
						},
						// Adds support for CSS Modules, but using SASS
						// using the extension .module.scss or .module.sass
						{
							test: sassModuleRegex,
							use: getStyleLoaders(
								{
									importLoaders: 3,
									sourceMap: isEnvProduction && shouldUseSourceMap,
									modules: {
										getLocalIdent: getCSSModuleLocalIdent,
									},
								},
								'sass-loader'
							),
						},
						// "file" loader makes sure those assets get served by WebpackDevServer.
						// When you `import` an asset, you get its (virtual) filename.
						// In production, they would get copied to the `build` folder.
						// This loader doesn't use a "test" so it will catch all modules
						// that fall through the other loaders.
						{
							loader: require.resolve('file-loader'),
							// Exclude `js` files to keep "css" loader working as it injects
							// its runtime that would otherwise be processed through "file" loader.
							// Also exclude `html` and `json` extensions so they get processed
							// by webpacks internal loaders.
							exclude: [/\.(js|mjs|jsx|ts|tsx)$/, /\.html$/, /\.json$/],
							options: {
								name: 'static/media/[name].[hash:8].[ext]',
							},
						},
						// ** STOP ** Are you adding a new loader?
						// Make sure to add the new loader(s) before the "file" loader.
					],
				},
			].filter(Boolean),
		},
		plugins: [
			// This gives some necessary context to module not found errors, such as
			// the requesting resource.
			new ModuleNotFoundPlugin(paths.root),
			// Makes some environment variables available to the JS code, for example:
			// if (process.env.NODE_ENV === 'production') { ... }. See `./env.js`.
			// It is absolutely essential that NODE_ENV is set to production
			// during a production build.
			// Otherwise React will be compiled in the very slow development mode.
			new webpack.DefinePlugin(env.stringified),
			// This is necessary to emit hot updates (currently CSS only):
			isEnvDevelopment && new webpack.HotModuleReplacementPlugin(),
			// Watcher doesn't work well if you mistype casing in a path so we use
			// a plugin that prints an error when you attempt to do this.
			// See https://github.com/facebook/create-react-app/issues/240
			isEnvDevelopment && new CaseSensitivePathsPlugin(),
			// If you require a missing module and then `npm install` it, you still have
			// to restart the development server for webpack to discover it. This plugin
			// makes the discovery automatic so you don't have to restart.
			// See https://github.com/facebook/create-react-app/issues/186
			isEnvDevelopment &&
			new WatchMissingNodeModulesPlugin(paths.nodeModules),
			isEnvProduction &&
			new MiniCssExtractPlugin({
				// Options similar to the same options in webpackOptions.output
			}),
			new webpack.DefinePlugin({
				DEVELOPMENT: JSON.stringify(isEnvDevelopment),
				PRODUCTION: JSON.stringify(isEnvProduction),
				BLOCKS_NS: 'trevorwp'
			}),
		].filter(Boolean),
		// Some libraries import Node modules but don't use them in the browser.
		// Tell webpack to provide empty mocks for them so importing them works.
		node: {
			module: 'empty',
			dgram: 'empty',
			dns: 'mock',
			fs: 'empty',
			http2: 'empty',
			net: 'empty',
			tls: 'empty',
			child_process: 'empty',
		},
		// Turn off performance processing because we utilize
		// our own hints via the FileSizeReporter
		performance: false,
	};
};
