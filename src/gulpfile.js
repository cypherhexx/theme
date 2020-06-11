"use strict";

/*
	- WordPress Theme directory name
*/
var projectTheme = 'livestyled';

/*
	- Node Modules
	--
*/
var gulp = require('gulp'),
	cache = require('gulp-cache'),
	clean = require('gulp-clean'),
	concat = require('gulp-concat'),
	imagemin = require('gulp-imagemin'),
	jshint = require('gulp-jshint'),
	minify = require('gulp-minify'),
	raname = require('gulp-rename'),
	sass = require('gulp-sass'),
	rimraf = require('rimraf'),
	runSequence = require('run-sequence');


/*
	- Directory vars
	--
*/
var config = {

	srcCss: 'css/',
	srcSass: 'scss/',
	srcJs: 'js/',
	srcJsLibs: 'js/libs/',
	srcImg: 'img/',
	srcFonts: 'fonts/',
	srcSVG: 'svg/',
	srcWordPress: '../',
	compiledCss: '../wp-content/themes/' + projectTheme + '/assets/css/',
	compiledJs: '../wp-content/themes/' + projectTheme + '/assets/js/',
	compiledJsLibs: '../wp-content/themes/' + projectTheme + '/assets/js/libs/',
	compressedImg: '../wp-content/themes/' + projectTheme + '/assets/img/',
	requiredFonts: '../wp-content/themes/' + projectTheme + '/assets/fonts/',
	requiredSVG: '../wp-content/themes/' + projectTheme + '/assets/svg/',

}


/*
	- Sass > Css
	--
*/
gulp.task('sass', function () {

	return gulp.src(config.srcSass + '**/*.scss')
		.pipe(sass({
			outputStyle: 'compressed'
		}).on('error', sass.logError))
		.pipe(gulp.dest(config.compiledCss));

});







/*
	JavaScript Error Checking
*/
gulp.task('jshint', function () {

	return gulp.src(config.srcJs + '*.js')
		.pipe(jshint())
		.pipe(jshint.reporter('default'));

});






/*
	- JavaScript
	--
*/
gulp.task('scripts', function () {

	return gulp.src(config.srcJs + '*.js')
		.pipe(jshint())
		.pipe(jshint.reporter('default'))
		.pipe(concat('app.js'))
		.pipe(minify())
		.pipe(gulp.dest(config.compiledJs));

});





/*
	- JavaScript - concatenation and minification of the js libaries in use
	--
*/
gulp.task('minifyLibs', function () {

	return gulp.src([
		'js/libs/ios-orientation.js',
		'js/libs/js.cookie.js',
		'js/libs/lazysizes.min.js',
		'js/libs/svgxuse.js'
	])
		.pipe(concat('app-libs.js'))
		.pipe(minify())
		.pipe(gulp.dest(config.compiledJsLibs));

});






/*
	- JavaScript - Moves any library which you don't want to be compiled into the main minified file, for example the back up jQuery file.
	--
*/
gulp.task('scriptLibs', function () {

	return gulp.src([
		'js/libs/jquery-2.2.4.min.js'
	])
		.pipe(gulp.dest(config.compiledJsLibs));

});





/*
	- Images
	--
*/
gulp.task('images', function () {

	return gulp.src(config.srcImg + '**/*')
		.pipe(cache(imagemin({
			interlaced: true
		})))
		.pipe(gulp.dest(config.compressedImg));

});






/*
	- Fonts
	--
*/
gulp.task('fonts', function () {

	return gulp.src(config.srcFonts + '**/*')
		.pipe(gulp.dest(config.requiredFonts));

});

/*
	- SVGs
	--
*/
gulp.task('svg', function () {

	return gulp.src(config.srcSVG + '**/*')
		.pipe(gulp.dest(config.requiredSVG));

});


/*
	- CSS for Admin styles
	--
*/
gulp.task('css', function () {

	return gulp.src(config.srcCss + '**/*')
		.pipe(gulp.dest(config.compiledCss));

});







/*
	- clean
	--
*/
gulp.task('clean', function (cb) {
	return rimraf(config.dist + '/**/*', cb);
});

gulp.task('copyfiles', function () {
	gulp.src(config.srcWordPress + '**/*')
		.pipe(gulp.dest(config.dist));
});






gulp.task('watch', function () {

	gulp.watch(config.srcSass + '**/*.scss', ['sass']);

	gulp.watch(config.srcJs + '*.js', ['scripts']);

	gulp.watch(config.srcJsLibs + '**/*.js', ['minifyLibs']);

	gulp.watch(config.srcJsLibs + '**/*.js', ['scriptLibs']);

	gulp.watch(config.srcImg + '*/**', ['images']);

	gulp.watch(config.srcFonts + '*/**', ['fonts']);

	gulp.watch(config.srcSVG + '*/**', ['svg']);

	gulp.watch(config.srcSVG + '*/**', ['css']);



});

gulp.task('testing', ['jshint']);

gulp.task('default', [

	'sass',
	'scripts',
	'minifyLibs',
	'scriptLibs',
	'images',
	'fonts',
	'svg',
	'css',
	'watch'

]);