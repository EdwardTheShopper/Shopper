jQuery(document).ready(function($) {
    // Target the second li element in the breadcrumb
    var secondLi = $('.woocommerce-breadcrumb li:nth-child(2)');
    // Check if the word "store" is present in the second li
    if (secondLi.text().toLowerCase().includes('store')) {
        // Translate or modify the content if "store" is found
        secondLi.text('חנות');
    }
});