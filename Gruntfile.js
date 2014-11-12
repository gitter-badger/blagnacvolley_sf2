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
          "web/css/bootstrap.css": "bower_components/bootstrap/less/bootstrap.less",

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
      css: {
        files: [
          {src: 'bower_components/bootstrap/dist/css/bootstrap-theme.min.css', dest: 'web/css/bootstrap-theme.min.css'},
          {src: 'bower_components/bootstrap-3-datepicker/css/datepicker3.css', dest: 'web/css/datepicker3.css'},
          {src: 'bower_components/dropzone/downloads/css/dropzone.css', dest: 'web/css/dropzone.css'},
          {src: 'bower_components/scheduler/codebase/dhtmlxscheduler.css', dest: 'web/css/scheduler/dhtmlxscheduler.css'},
          {src: 'bower_components/redactor/redactor/redactor.css', dest: 'web/css/redactor/redactor/redactor.css'},
          {src: 'bower_components/font-awesome/css/font-awesome.min.css', dest: 'web/css/font-awesome/font-awesome.min.css'},
          {src: 'bower_components/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css', dest: 'web/css/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css'},
          {cwd: 'bower_components/font-awesome/fonts/', src: '**/*', dest: 'web/css/fonts/', expand: true  }
        ]
      },
      javascript: {
        files: [
          { src: 'bower_components/modernizr/modernizr.js', dest: 'web/js/modernizr.js' },
          { src: 'bower_components/bootstrap-3-datepicker/js/bootstrap-datepicker.js', dest: 'web/js/bootstrap-datepicker.js' },
          { src: 'bower_components/bootstrap-3-datepicker/js/locales/bootstrap-datepicker.fr.js', dest: 'web/js/bootstrap-datepicker.fr.js' },
          { src: 'bower_components/jquery/dist/jquery.min.js',              dest: 'web/js/jquery.min.js' },
          { src: 'bower_components/jquery/dist/jquery.min.map',             dest: 'web/js/jquery.min.map' },
          { src: 'bower_components/dropzone/downloads/dropzone.min.js',     dest: 'web/js/dropzone.min.js' },
          { src: 'bower_components/scheduler/codebase/dhtmlxscheduler.js',  dest: 'web/js/scheduler/dhtmlxscheduler.js' },
          { src: 'bower_components/scheduler/codebase/sources/ext/dhtmlxscheduler_readonly.js',  dest: 'web/js/scheduler/dhtmlxscheduler_readonly.js' },
          { src: 'bower_components/scheduler/codebase/sources/ext/dhtmlxscheduler_editors.js',  dest: 'web/js/scheduler/dhtmlxscheduler_editors.js' },
          { src: 'bower_components/scheduler/codebase/sources/ext/dhtmlxscheduler_readonly.js.map',  dest: 'web/js/scheduler/dhtmlxscheduler_readonly.js.map' },
          { src: 'bower_components/scheduler/codebase/sources/dhtmlxscheduler.js.map',  dest: 'web/js/scheduler/sources/dhtmlxscheduler.js.map' },
          { src: 'bower_components/scheduler/codebase/locale/locale_fr.js', dest: 'web/js/scheduler/locale_fr.js' },
          { src: 'bower_components/moment/min/moment.min.js',               dest: 'web/js/moment/moment.min.js' },
          { src: 'bower_components/moment/min/locales.min.js',              dest: 'web/js/moment/locales.min.js' },
          { src: 'bower_components/redactor/redactor/redactor.js',          dest: 'web/js/redactor/redactor/redactor.js' },
          { src: 'lib/redactor/locale/fr.js',                               dest: 'web/js/redactor/redactor/locale/fr.js' },
          { src: 'lib/redactor/filemanager.js',                             dest: 'web/js/redactor/redactor/filemanager.js' },
          { src: 'bower_components/bootstrap/js/popover.js',                dest: 'web/js/bootstrap/popover.js' },
          { src: 'bower_components/bootstrap/js/tooltip.js',                dest: 'web/js/bootstrap/tooltip.js' },

          // CKEDITOR
          { cwd: 'bower_components/redactor/', src: '**/*', dest: 'web/js/redactor/', expand: true  }
        ]
      },
      images: {
        files: [
          {
            cwd: 'bower_components/dropzone/downloads/images/',  // set working folder / root to copy
            src: '**/*',           // copy all files and subfolders
            dest: 'web/images/',    // destination folder
            expand: true           // required when using cwd
          },
          {
            cwd: 'bower_components/scheduler/codebase/imgs/',  // set working folder / root to copy
            src: '**/*',           // copy all files and subfolders
            dest: 'web/css/scheduler/imgs/',    // destination folder
            expand: true           // required when using cwd
          },
          {
            cwd: 'bower_components/scheduler/codebase/imgs_dhx_terrace/',  // set working folder / root to copy
            src: '**/*',           // copy all files and subfolders
            dest: 'web/css/scheduler/imgs_dhx_terrace/',    // destination folder
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