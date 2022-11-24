let mix = require("laravel-mix");

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
mix
  .setPublicPath("/")
  .sass("resources/scss/style.scss", "resources/assets/css/css1.css")
  .sass(
    "node_modules/sweetalert2/src/sweetalert2.scss",
    "resources/assets/css/css2.css"
  )
  .styles(
    ["resources/assets/css/css1.css", "resources/assets/css/css2.css"],
    "public/css/styles.css"
  )
  .scripts(
    [
      "node_modules/jquery/dist/jquery.js",
      "node_modules/bootstrap/dist/js/bootstrap.bundle.js",
      "node_modules/jquery-ui/dist/jquery-ui.js",
      "node_modules/sweetalert2/dist/sweetalert2.js",
      "node_modules/jquery-mask-plugin/src/jquery.mask.js",
      "node_modules/chart.js/dist/chart.js",
      "node_modules/@fortawesome/fontawesome-free/js/fontawesome.js",
      "node_modules/@fortawesome/fontawesome-free/js/solid.js",
      "node_modules/@fortawesome/fontawesome-free/js/regular.js",
      "node_modules/@fortawesome/fontawesome-free/js/brands.js",
    ],
    "public/js/scripts.js"
  );
mix.options({
  postCss: [
    require("postcss-discard-comments")({
      removeAll: true,
    }),
  ],
  uglify: {
    comments: false,
  },
});
