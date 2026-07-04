(function () {
  'use strict';

  var categoryId = 'elementor-panel-category-ukits-custom-element';

  function moveCategoryToTop() {
    var category = document.getElementById(categoryId);

    if (!category || !category.parentNode) {
      return;
    }

    var parent = category.parentNode;
    var firstCategory = parent.querySelector('[id^="elementor-panel-category-"]');

    if (firstCategory && firstCategory !== category) {
      parent.insertBefore(category, firstCategory);
    }
  }

  document.addEventListener('DOMContentLoaded', moveCategoryToTop);
  window.addEventListener('load', moveCategoryToTop);

  var observer = new MutationObserver(moveCategoryToTop);
  observer.observe(document.documentElement, {
    childList: true,
    subtree: true
  });
}());
