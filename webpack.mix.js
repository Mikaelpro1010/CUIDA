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

mix.options({
  cleanCss: {
    level: {
      1: {
        specialComments: "none",
      },
    },
  },

  postCss: [require("postcss-discard-comments")({ removeAll: true })],
  purgeCss: true,
  uglify: {
    comments: false,
  },
});

mix
  .setPublicPath("/")
  .sass("resources/scss/style.scss", "resources/assets/css/css1.css")
  .sass(
    "node_modules/sweetalert2/src/sweetalert2.scss",
    "resources/assets/css/css2.css"
  )
  .styles(
    [
      "resources/assets/css/main.css",
      "resources/assets/css/input.css",
      "resources/assets/css/output.css",
      "resources/assets/css/bootstrap.min.css",
      "resources/assets/css/css1.css",
      "resources/assets/css/css2.css",
      "resources/assets/css/custom_adm.css",
      "resources/assets/css/custom_login.css",
      "node_modules/@fortawesome/fontawesome-free/css/svg-with-js.css",
    ],
    "public/css/styles.css"
    )
    .scripts(
      [
        "resources/assets/js/darkMode.js",
        "resources/assets/js/logout.js",
        "resources/assets/js/delete_elemento.js",
        "node_modules/jquery/dist/jquery.js",
        "resources/assets/js/scripts.js",
        "node_modules/bootstrap/dist/js/bootstrap.bundle.js",
        "node_modules/jquery-ui/dist/jquery-ui.js",
        "node_modules/sweetalert2/dist/sweetalert2.js",
        "node_modules/jquery-mask-plugin/src/jquery.mask.js",
        "node_modules/chart.js/dist/chart.js",
        "resources/assets/js/custom_adm.js",
        "node_modules/@fortawesome/fontawesome-free/js/all.js",
    ],
    "public/js/scripts.js"
  );