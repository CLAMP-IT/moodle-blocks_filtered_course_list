"use strict";

module.exports = function (grunt) {

    // We need to include the core Moodle grunt file too, otherwise we can't run tasks like "amd".
    require("grunt-load-gruntfile")(grunt);
    grunt.loadGruntfile("../../Gruntfile.js");

    // Load all grunt tasks.
    grunt.loadNpmTasks("grunt-contrib-less");
    grunt.loadNpmTasks("grunt-contrib-watch");
    grunt.loadNpmTasks("grunt-contrib-clean");

    grunt.initConfig({
        watch: {
            // If any .less file changes in directory "less" then run the "less" task.
            less: {
                files: "less/*.less",
                tasks: ["less"]
            },
            amd: {
                files: "amd/src/*.js",
                tasks: ["amd"]
            }
        },
        less: {
            // Production config is also available.
            development: {
                options: {
                    // Specifies directories to scan for @import directives when parsing.
                    // Default value is the directory of the source, which is probably what you want.
                    paths: ["less/"],
                    compress: false
                },
                files: {
                    "styles.css": "less/styles.less"
                }
            },
        },
        eslint: {
            amd: {src: "amd/src"}
        },
        uglify: {
            amd: {
                files: {
                    "amd/build/accordion.min.js": ["amd/src/accordion.js"]
                },
                options: {report: 'none'}
            }
        }
    });
    // The default task (running "grunt" in console).
    grunt.registerTask("default", ["less", "eslint", "uglify"]);

};
