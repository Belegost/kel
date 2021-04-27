'use strict';

const gulp = require('gulp');

const sass = require('gulp-sass');
const cleanCSS = require('gulp-clean-css');

const buildCss = () => {
    return gulp.src("./scss/**/*.scss")
        .pipe(sass())
        .pipe(cleanCSS({level: 2}))
        .pipe(gulp.dest("../public/css/"));
};
const watchCss = () => {
    return gulp.watch("./scss/**/*.scss", gulp.series('buildCss'));
};


exports.buildCss = buildCss;
exports.watchCss =  watchCss;