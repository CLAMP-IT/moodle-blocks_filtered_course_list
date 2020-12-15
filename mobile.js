var that = this;

that.toggle = function(rubric) {
    if (rubric.expanded) {
        rubric.expanded = false;
    } else {
        rubric.expanded = true;
    }
}

that.openCategory = function(id) {
    that.NavController.push('CoreCoursesCategoriesPage', { categoryId: id });
}
