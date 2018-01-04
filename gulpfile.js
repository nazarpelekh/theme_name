var autoprefixer = require('autoprefixer');
var notify = require('gulp-notify');
var assets  = require('postcss-assets');
var gulp = require('gulp');
var livereload = require('gulp-livereload');
var browserSync  = require('browser-sync'); // Подключаем Browser Sync
var sass = require('gulp-sass');
var postcss = require('gulp-postcss');
var uglify = require('gulp-uglify');
var webserver = require('gulp-webserver');


var handleError = function (err) {
    console.log(err.name, ' in ', err.plugin, ': ', err.message);
    // console.log(err.getStack());
    process.exit(1);
};


gulp.task('sass', function () {

    var processors = [
        assets({
            basePath: '/wp-content/themes/welness',
            loadPaths: ['fonts/', 'images/']
        }),
        autoprefixer
    ];

    return gulp.src('wp-content/themes/welness/style/style.scss')
        .pipe(sass({
                outputStyle: 'compact' // :nested, :expanded, :compact, :compressed
            }).on('error', notify.onError('<%= error.message %>'))
        )
        .pipe(gulp.dest('wp-content/themes/welness/style'))
        .pipe(browserSync.reload({stream: true})) // Обновляем CSS на странице при изменении
});

gulp.task('browser-sync', function() { // Создаем таск browser-sync
  browserSync({ // Выполняем browserSync
    // server: { // Определяем параметры сервера
    //   baseDir: 'wp-content/themes/ace' // Директория для сервера - app
    // },
    port: 8000,
    proxy: 'localhost/ace', //remove localhost if you don't use MAMP, just 'ace'
    notify: false // Отключаем уведомления
  });
});
// Watch

gulp.task('watch', ['browser-sync'], function () {

  gulp.watch('wp-content/themes/ace/style/**/*.scss', ['sass']);
  gulp.watch('wp-content/themes/ace/**/*.php', browserSync.reload); // Наблюдение за PHP файлами в корне проекта
  gulp.watch('wp-content/themes/ace/*.php', browserSync.reload); // Наблюдение за PHP файлами в корне проекта
  gulp.watch('wp-content/themes/ace/*.css', browserSync.reload); // Наблюдение за CSS файлами в корне проекта
  gulp.watch('wp-content/themes/ace/*.js', browserSync.reload);   // Наблюдение за JS файлами в папке js

});
