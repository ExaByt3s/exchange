var gulp = require('gulp'),
    concat = require('gulp-concat'),
    sass = require('gulp-sass'),
    uglify = require('gulp-uglify'),
    sri = require('gulp-sri');


/**
 * Compiles sass to css and moves to assets in public_html.
 *
 * Source: assets/sass/main.scss
 * Destination: public_html/assets/main.css
 * Observable: assets/sass/*
 */
gulp.task('compile-sass', function () {
    gulp.src('assets/sass/main.scss')
        .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
        .pipe(gulp.dest('public_html/assets/'))
        .pipe(sri())
        .pipe(gulp.dest('.'));

    console.log('Task: compile-sass - done!');
});


/**
 * Compiles vendors sass to css and moves to assets in public_html.
 *
 * Source: assets/sass/vendors/ ** /*.scss
 * Destination: public_html/assets/vendors.css
 */
gulp.task('compile-vendors-sass', function () {
    gulp.src('assets/sass/vendors/**/*.scss')
        .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
        .pipe(concat('vendors.css'))
        .pipe(gulp.dest('public_html/assets/'))
        .pipe(sri())
        .pipe(gulp.dest('.'));

    console.log('Task: compile-vendors-sass - done!');
});


/**
 * Concatenates js and moves to assets in public_html.
 *
 * Source: assets/scripts/*.js
 * Destination: public_html/assets/main.js
 * Observable: assets/scripts/*
 */
gulp.task('minify-js', function () {
    gulp.src('assets/scripts/*.js')
        .pipe(concat('main.js'))
        .pipe(uglify())
        .pipe(gulp.dest('public_html/assets/'))
        .pipe(sri())
        .pipe(gulp.dest('.'));

    console.log('Task: minify-js - done!');
});


/**
 * Concatenates vendors js and moves to assets in public_html.
 *
 * Source: assets/scripts/ ** /*.js
 * Destination: public_html/assets/vendors/ ** /*.js
 */
gulp.task('minify-vendors-js', function () {
    gulp.src([
            'assets/scripts/vendors/jquery_3.1.1/*',
            'assets/scripts/vendors/knockout_3.4.0/*',
            'assets/scripts/vendors/tether_1.3.3/*',
            'assets/scripts/vendors/bootstrap_4.0.0a4/*'
        ])
        .pipe(concat('vendors.js'))
        .pipe(uglify())
        .pipe(gulp.dest('public_html/assets/'))
        .pipe(sri())
        .pipe(gulp.dest('.'));

    console.log('Task: minify-vendors-js - done!');
});


gulp.task('watch', function() {
    gulp.watch('assets/sass/*', ['compile-sass']);
    gulp.watch('assets/scripts/*', ['minify-js']);
});


gulp.task('default', ['compile-sass', 'minify-js', 'compile-vendors-sass', 'minify-vendors-js']);