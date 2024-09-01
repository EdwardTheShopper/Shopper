jQuery(document).ready(function() {

    /* handle search-icon click */
    jQuery('.mobile-nav-wrapper .search').click(function(e) {
        e.preventDefault();
        setTimeout(function() { // wait in order to properly catch the widget
            if(jQuery('.header-search.active').length) { 
                // search widget is active
                jQuery('.header-search .dgwt-wcas-search-input').focus(); // set focus on the search bar input
                jQuery('html').css('overflow', 'hidden'); // disable background scrolling
            }
            else // enable background scrolling
                jQuery('html').css('overflow', 'auto');
        }, 200);
    });

    /* close the search widget when clicking on the header */
    jQuery('.site-header .header-main').click(function() {
        var search_widget = jQuery('.header-search.active');
        if(search_widget.length) { // active
            jQuery('html').css('overflow', 'auto'); // enable background scrolling
            
            search_widget.removeClass('active');
            jQuery('.mobile-nav-wrapper .search').removeClass('active'); // reset search-button decoration
        }
    });

    /* prevent the search widget from closing when clicking on the search input or other elements in the search widget */
    jQuery('.header-search').click(function(event) {
        event.stopPropagation();
    });
});