jQuery(document).ready(function() {

    /* handle search-icon click */
    jQuery('.mobile-nav-wrapper .search').click(function() {
        setTimeout(function() { // wait in order to properly catch the widget
            if(jQuery('.header-search.active').length) { 
                // search widget is active
                jQuery('.header-search .dgwt-wcas-search-input').focus(); // set focus on the search bar input

                // disable scrolling of the page (behind the widget)
                jQuery('#main.site-primary').css('position', 'fixed');
                jQuery('.site-footer').css('display', 'none'); // for better design
            }
            else { // bring back scrolling
                jQuery('.site-footer').css('display', 'block');
                jQuery('#main.site-primary').css('position', 'relative');
            }
        }, 300);
    });

    /* close the search widget when clicking on the header */
    jQuery('.site-header .header-main').click(function() {
        var search_widget = jQuery('.header-search.active');
        if(search_widget.length) { // active
            // bring back scrolling
            jQuery('#main.site-primary').css('position', 'relative');
            jQuery('.site-footer').css('display', 'block');
            
            search_widget.removeClass('active');
            jQuery('.mobile-nav-wrapper .search').removeClass('active'); // reset search-button decoration
        }
    });

    /* prevent the search widget from closing when clicking on the search input or other elements in the search widget */
    jQuery('.header-search').click(function(event) {
        event.stopPropagation();
    });
});