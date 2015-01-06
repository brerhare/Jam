'use strict';

var gulp = require('gulp');

gulp.task('watch', ['styles'] ,function () {
  gulp.watch('src/{app,assets/css,assets/js}/**/*.scss', ['styles']);
  gulp.watch('src/{app,assets/js}/**/*.js', ['scripts']);
  gulp.watch('src/assets/images/**/*', ['images']);
  gulp.watch('bower.json', ['wiredep']);
});
