const { mix } = require('laravel-mix');
const path = require('path');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.webpackConfig({
    resolve: {
        alias: {
            'elfdict': path.resolve(__dirname, 'resources/assets/js/_ed/'),

            'ed-components/error-list': 'elfdict/components/error-list.jsx',
            'ed-components/markdown-editor': 'elfdict/components/markdown-editor.jsx',
            'ed-components/language-select': 'elfdict/components/language-select.jsx',

            'ed-config': 'elfdict/config.js',
            'ed-form': 'elfdict/form.js',
            'ed-promise': 'elfdict/promise.js',
            'ed-session-storage-state': 'elfdict/session-storage-state.js'
        }
    }
});

mix.extract([
    'react', 'react-dom', 'react-router-dom',
    'react-redux', 'redux', 'redux-thunk',
    'react-autosuggest',
    'html-to-react',
    'axios',
    'classnames',
    'smoothscroll-polyfill',
    'ed-components/error-list',
    'ed-components/markdown-editor',
    'ed-components/language-select',
    'ed-config',
    'ed-form',
    'ed-promise',
    'ed-session-storage-state'
]);

mix.react([
    'resources/assets/js/navigation.js',
    'resources/assets/js/search/index.jsx'
], 'public/js/global.js');

mix.combine([
    'resources/assets/js/_shared/glaemscribe/glaemscribe.js',
    'resources/assets/js/_shared/glaemscribe/charsets/tengwar_ds_parmaite.cst.js',
    'resources/assets/js/_shared/glaemscribe/modes/adunaic.glaem.js',
    'resources/assets/js/_shared/glaemscribe/modes/blackspeech.glaem.js',
    'resources/assets/js/_shared/glaemscribe/modes/quenya.glaem.js',
    'resources/assets/js/_shared/glaemscribe/modes/sindarin-beleriand.glaem.js',
    'resources/assets/js/_shared/glaemscribe/modes/sindarin.glaem.js',
    'resources/assets/js/_shared/glaemscribe/modes/telerin.glaem.js',
    'resources/assets/js/_shared/glaemscribe/modes/westron.glaem.js',
], 'public/js/glaemscribe.js');

mix.js([
    'node_modules/babel-polyfill/dist/polyfill.js',
    'resources/assets/js/_ie/polyfills.js',
], 'public/js/ie.js');

mix.js([
    'resources/assets/js/markdown/index.jsx'
], 'public/js/markdown.js');

mix.js([
    'resources/assets/js/sentence/index.jsx'
], 'public/js/sentence.js');

mix.js([
    'resources/assets/js/sentence/admin.jsx'
], 'public/js/sentence-admin.js');

mix.js([
    'resources/assets/js/comment/index.jsx'
], 'public/js/comment.js');

mix.js([
    'resources/assets/js/translation/admin.jsx'
], 'public/js/translation-admin.js');

mix.js([
    'resources/assets/js/flashcard/index.jsx'
], 'public/js/flashcard.js');

//mix.copy('node_modules/tengwar/tengwar-parmaite.*', 'public/fonts/tengwar'); <~~ the parmaite font kit is/will be corrupted!
mix.sass('resources/assets/sass/app.scss', 'public/css');
mix.sass('resources/assets/sass/app.flashcard.scss', 'public/css');
mix.sass('resources/assets/sass/app.sentences.scss', 'public/css');
