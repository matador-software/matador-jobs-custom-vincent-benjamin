// Load plugins
var gulp    = require('gulp'),
	wpPot   = require('gulp-wp-pot');

gulp.task('translate', function () {
	return gulp.src('./**/*.php')
		.pipe(wpPot({
			domain: 'matador-custom-vincent-benjamin',
			package: 'Matador Jobs Custom Vincent Benjamin Extension'
		}))
		.pipe(gulp.dest('languages/matador-custom-vincent-benjamin.pot'));
});

gulp.task('default', function () {
	gulp.start('translate');
});