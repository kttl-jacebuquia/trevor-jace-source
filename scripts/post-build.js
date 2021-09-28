const path = require('path');
const fs = require('fs-extra');
const { v4: uuid } = require('uuid');

const themeRoot = path.resolve(__dirname, '../theme');
const buildManifestFilePath = path.resolve(themeRoot, 'build.php');

console.log('Generating build version...');

// Generate build version
const version = uuid();

// Output build version to PHP, defining global constant
const contents = `<?php define( 'TREVORWP_STATIC_VERSION', '${version}' );\n`;

// output build data to PHP file
fs.outputFileSync(buildManifestFilePath, contents);
