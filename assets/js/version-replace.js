const fs = require('fs-extra');
const replace = require('replace-in-file');

const pluginFiles = ['includes/**/*', 'templates/*', 'src/*', 'product-real-estate.php'];

const { version } = JSON.parse(fs.readFileSync('package.json'));

replace({
    files: pluginFiles,
    from: /PRODUCTPLACE_SINCE/g,
    to: version,
});
