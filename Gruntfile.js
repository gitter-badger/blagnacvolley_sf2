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
          "web/css/progress.css": "src/BV/FrontBundle/Resources/public/less/progress.less",
          "web/css/bootstrap.css": "web/vendor/bootstrap/less/bootstrap.less",

          // Admin files
          "web/css/styles_admin.css": "src/BV/AdminBundle/Resources/public/styles.less"
        }
      }
    },
    copy: {
      fonts: {
        files: [
          {cwd: 'bower_components/bootstrap/fonts/', src: '**/*', dest: 'web/fonts/', expand: true  }
        ]
      },
      javascript: {
        files: [
          { src: 'lib/redactor/locale/fr.js',                               dest: 'web/js/redactor/redactor/locale/fr.js' },
          { src: 'lib/redactor/filemanager.js',                             dest: 'web/js/redactor/redactor/filemanager.js' }
        ]
      },
      images: {
        files: [
          {
            cwd: 'web/vendor/dropzone/downloads/images/',  // set working folder / root to copy
            src: '**/*',           // copy all files and subfolders
            dest: 'web/images/',    // destination folder
            expand: true           // required when using cwd
          }
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
      },

      js_files: {
        files: ['Gruntfile.js'], // which files to watch
        tasks: ['build'],
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