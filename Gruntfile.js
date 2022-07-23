module.exports = function (grunt) {

    const sass = require('node-sass');

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        // Compile SASS files into minified CSS.
        sass: {
            options: {
                implementation: sass,
                sourceMap: true,
                outputStyle: 'compressed'
            },
            dist: {
                files: {
                    'assets/css/main.min.css': 'assets/css/scss/main.scss'
                }
            }
        },

        // Validate our Javascript.
        jshint: {
            files: [
                'Gruntfile.js',
                'assets/js/*.js',
                '!assets/js/main.min.js'
            ],
            options: {
                esversion: 6, // Allow ES6
                globals: {    // Tell JSHint about global variables defined elsewhere
                    jQuery: true,
                    console: true,
                    module: true,
                    document: true
                }
            }
        },

        // Concatenate and minify our JavaScript files.
        uglify: {
            options: {
                sourceMap: true,
                sourceMapName: 'assets/js/main.min.js.map'
            },
            scripts: {
                files: {
                    'assets/js/main.min.js': [
                        'assets/js/**/*.js',
                        '!assets/js/main.min.js'  // Don't include ourself
                    ]
                }
            }
        },

        // Watch these files and notify of changes.
        watch: {
            sass: {
                files: [
                    'assets/css/scss/**/*.scss',
                    'acf/blocks/**/*.scss'
                ],
                tasks: ['sass']
            },
            scripts: {
                files: [
                    'assets/js/**/*.js',
                    '!assets/js/main.min.js'
                ],
                tasks: ['jshint', 'uglify']
            },
        }
    });

    // Enable modules
    grunt.loadNpmTasks('grunt-sass');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-jshint');

    // Establish tasks we can run from the terminal.
    grunt.registerTask('build', ['sass', 'jshint', 'uglify']);
    grunt.registerTask('default', ['build', 'watch']);
};
