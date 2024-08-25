(function ($) {
  "use strict";

  // Define the woocommercePriceSlider function
  function woocommercePriceSlider() {
    $('.widget_custom_product_categories ul.children').closest('li').addClass("cat-parent");
    $(".widget_custom_product_categories ul > li.cat-parent").append('<span class="subDropdown plus"></span>');
    $('.widget_custom_product_categories ul.children input[checked]').closest('li.cat-parent').addClass("current-cat");

    // Ensure the event listener is set up correctly
    $(".subDropdown").off("click").on("click", function() {
      $(this).toggleClass("plus");
      $(this).toggleClass("minus");
      $(this).parent().find("ul").slideToggle();
    });
  }

  // Initialize the function on document ready
  $(document).ready(function() {
    woocommercePriceSlider();
  });

})(jQuery);
