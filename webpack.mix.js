const mix = require('laravel-mix');
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

mix.setPublicPath('public')
   .setResourceRoot('../') // Turns assets paths in css relative to css file

   // Compile SASS files
   .sass('resources/sass/core/core.scss', 'css/core.css')
   .sass('node_modules/dropzone/src/dropzone.scss', 'css/dropzone.css')
   .sass('resources/sass/_global.scss', 'css/fontawesome.css')

   // Compile JavaScript and Vue components
   .js('resources/js/mainApp.js', 'js/core.js').vue({ version: 2 })

   // Extract vendor libraries to a separate file
   .extract([
       'jquery',
       'bootstrap',
       'popper.js',
       'axios',
       'sweetalert2',
       'lodash'
   ])

   // Versioning and source maps for production
   .options({
       processCssUrls: false,
       terser: {
           extractComments: false,
       }
   })
   .sourceMaps();

// Extend Webpack configuration
mix.webpackConfig({
    resolve: {
        alias: {
            '@app': path.resolve(__dirname, 'resources/js/crm/'),
            '@core': path.resolve(__dirname, 'resources/js/core/')
        }
    },
    output: {
        chunkFilename: 'js/[name].js?id=[chunkhash]', // for long-term caching
    }
});

if (mix.inProduction()) {
    mix.version();
} else {
    // Enable source maps in development for better debugging
    mix.sourceMaps(true, 'source-map');
}
