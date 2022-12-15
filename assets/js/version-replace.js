const fs = require('fs-extra');
const replace = require('replace-in-file');

const pluginFiles = ['includes/**/*', 'templates/*', 'src/*', 'alert-real-estate.php'];

const { version } = JSON.parse(fs.readFileSync('package.json'));

replace({
    files: pluginFiles,
    from: /ALERTPLACE_SINCE/g,
    to: version,
});
