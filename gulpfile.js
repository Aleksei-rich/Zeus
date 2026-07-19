const {src, dest, series, watch} = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const csso = require('gulp-csso');
const include = require('gulp-file-include');
const htmlmin = require('gulp-htmlmin');
const uglify = require('gulp-uglify');
const del = require('del');
const concat = require('gulp-concat');
const autoprefixer = require('gulp-autoprefixer');
const sync = require('browser-sync').create();

function html() {
  return src('html-src/**.html')
    .pipe(include({
      prefix: '@@'
    }))
    .pipe(htmlmin({
      // collapseWhitespace: true
    }))
    .pipe(dest('assets'));
}

function images() {
  return src('html-src/img/**')
    .pipe(dest('assets/img'));
}

function fonts() {
  return src('html-src/fonts/**')
    .pipe(dest('assets/fonts'));
}

function scss() {
  return src('html-src/scss/**.scss')
    .pipe(sass())
    .pipe(autoprefixer({
      browsers: ['last 2 versions']
    }))
    .pipe(csso())
//    .pipe(concat('styles.css'))
    .pipe(dest('assets/css'));
}


function libs() {
  return src([
    'html-src/libs/jquery-3.6.0.min.js',
    'html-src/libs/owlcarousel/owl.carousel.js',
    'html-src/libs/fancybox/fancybox.js'
    ])
    .pipe(uglify())
    .pipe(concat('scripts.js'))
    .pipe(dest('assets/js'));
}
function js() {
  return src('html-src/js/main.js')
     .pipe(uglify())
    .pipe(dest('assets/js'));
}

function clear() {
  return del('assets');
}

function serve() {
  sync.init({
    server: './assets'
  });

  watch('html-src/**.html', series(html)).on('change', sync.reload);
  watch('html-src/parts/**.html', series(html)).on('change', sync.reload);
  watch('html-src/scss/**.scss', series(scss)).on('change', sync.reload);
  watch('html-src/scss/import/**.scss', series(scss)).on('change', sync.reload);
  watch('html-src/fonts/**', series(fonts)).on('change', sync.reload);
  watch('html-src/js/**.js', series(js)).on('change', sync.reload);
  watch('html-src/libs/**.js', series(libs)).on('change', sync.reload);
  watch('html-src/libs/**/**.js', series(libs)).on('change', sync.reload);
  watch('html-src/libs/**/**.css', series(scss)).on('change', sync.reload);
  watch('html-src/img/**', series(images)).on('change', sync.reload);
}

exports.build = series(clear, scss, images, fonts, libs, js);
exports.serve = series(clear, scss, images, fonts, libs, js, html, serve);
exports.clear = clear;