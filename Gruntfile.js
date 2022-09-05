module.exports = function (grunt) {

    grunt.initConfig({
        // Compile SASS files into minified CSS.
        sass: {
            options: {
                sourcemap: 'auto',
                style: 'compressed',
                noCache: true
            },
            dist: {
                files: {
                    'assets/css/main.min.css': 'assets/css/scss/main.scss'
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
            }
        }
    });

    // Enable modules
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-watch');

    // Establish tasks we can run from the terminal.
    grunt.registerTask('build', ['sass']);
    grunt.registerTask('default', ['build', 'watch']);
};
