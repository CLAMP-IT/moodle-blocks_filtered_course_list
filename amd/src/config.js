define([], function() {
    window.requirejs.config({
        paths: {
            "cookie": M.cfg.wwwroot + '/blocks/filtered_course_list/js/js.cookie-2.2.0.min',
        },
        shim: {
            "cookie": {exports: 'cookie'},
        }
    });
});
