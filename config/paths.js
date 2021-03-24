const path = require('path');
const fs = require('fs');

const appDirectory = fs.realpathSync(process.cwd());
const resolveApp = relativePath => path.resolve(appDirectory, relativePath);

module.exports = {
    root: appDirectory,
    appPackageJson: resolveApp('package.json'),
	nodeModules: resolveApp('node_modules'),
    src: resolveApp('src'),
    build: resolveApp('build'),
	tailwindCssConf: resolveApp('tailwind.config.js'),
    // - Plugin
    pluginJSMain: resolveApp('src/plugin/js/main'),
    pluginJSBlocks: resolveApp('src/plugin/js/blocks'),
    pluginCSSMain: resolveApp('src/plugin/css/main.scss'),
    pluginCSSDevMain: resolveApp('src/plugin/css-dev/main.js'),
	pluginPHP: resolveApp('plugin'),
    // - Theme
    themeJSAdmin: resolveApp('src/theme/js/admin'),
    themeJSFrontEnd: resolveApp('src/theme/js/frontend'),
    themeJSFSiteBanners: resolveApp('src/theme/js/site-banners'),
    themeCSSFrontend: resolveApp('src/theme/css/frontend/main.scss'),
    themeCSSDevFrontend: resolveApp('src/theme/css-dev/frontend.js'),
    themeCSSAdmin: resolveApp('src/theme/css/admin/main.scss'),
    themeCSSDevAdmin: resolveApp('src/theme/css-dev/admin.js'),
	themePHP: resolveApp('theme'),
};
