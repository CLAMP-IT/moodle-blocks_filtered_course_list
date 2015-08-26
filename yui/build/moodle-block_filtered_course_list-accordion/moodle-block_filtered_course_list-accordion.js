YUI.add('moodle-block_filtered_course_list-accordion', function (Y, NAME) {

"use_strict";
/*jsline browser: true*/
/*global M*/
/*global Y*/

var FCLA;

M.block_filtered_course_list = M.block_filtered_course_list || {};
FCLA = M.block_filtered_course_list.accordion = {};

FCLA.init = function (params) {
  Y.on('domready', function () {
    sectionTitles = Y.all('.block_filtered_course_list .course-section');
    sectionTitles.each(function (title) {
      if (!(title.hasClass('expanded'))) {
        title.addClass('collapsed');
      }
      var html = title.getHTML();
      title.setHTML('<a href="#">' + html + '</a>');
      title.on('click', function (e) {
        e.preventDefault();
        FCLA.toggle(this);
      });
    });
  });
  console.log(params);
};

FCLA.toggle = function (title) {
  if (title.hasClass('collapsed')) {
    title.removeClass('collapsed');
    title.addClass('expanded');
  }
  else if (title.hasClass('expanded')) {
    title.removeClass('expanded');
    title.addClass('collapsed');
  }
};


}, '@VERSION@');
