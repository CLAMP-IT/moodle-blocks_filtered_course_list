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
      title.addClass('collapsed');
      // var anchor = Y.Node.create('<a href="#"></a>');
      // anchor.append(title.replace(anchor));
      // title.append(anchor);
      // console.log(title.getHTML());
      var html = title.getHTML();
      title.setHTML('<a href="#">' + html + '</a>');
      title.on('click', function (e) {
        e.preventDefault();
        FCLA.toggle(this);
      });
    });
  });
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
