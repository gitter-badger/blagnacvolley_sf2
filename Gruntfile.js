module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    less: {
      development: {
        options: {
          compress: true,
          yuicompress: true,
          optimization: 2
        },
        files: {
          // target.css file: source.less file
          "web/css/styles.css": "src/BV/FrontBundle/Resources/public/less/styles.less",
          "web/css/bootstrap.css": "bower_components/bootstrap/less/bootstrap.less"
        }
      }
    },
    copy: {
      css: {
        files: [
          {src: 'bower_components/bootstrap/dist/css/bootstrap-theme.min.css', dest: 'web/css/bootstrap-theme.min.css'},
          {src: 'bower_components/bootstrap-3-datepicker/css/datepicker3.css', dest: 'web/css/datepicker3.css'}
        ]
      },
      javascript: {
        files: [
          { src: 'bower_components/modernizr/modernizr.js', dest: 'web/js/modernizr.js' },
          { src: 'bower_components/bootstrap-3-datepicker/js/bootstrap-datepicker.js', dest: 'web/js/bootstrap-datepicker.js' },
          { src: 'bower_components/bootstrap-3-datepicker/js/locales/bootstrap-datepicker.fr.js', dest: 'web/js/bootstrap-datepicker.fr.js' },
          { src: 'bower_components/jquery/dist/jquery.min.js', dest: 'web/js/jquery.min.js' },
          { src: 'bower_components/jquery/dist/jquery.min.map', dest: 'web/js/jquery.min.map' }
        ]
      }
    },
    watch: {
      styles: {
        files: ['src/**/*.less'], // which files to watch
        tasks: ['less'],
        options: {
          nospawn: true
        }
      }
    }
  });

  // Load the plugin that provides the "uglify" task.
  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-watch');

  // Default task(s).
  grunt.registerTask('default', ['watch']);
  grunt.registerTask('build', ['less', 'copy']);

};