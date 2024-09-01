(function ($) {
    "use strict";
    $(document).on('bacolaShopPageInit', function () {
        bacolaThemeModule.sidebarfilter();
    });
    bacolaThemeModule.sidebarfilter = function() {
        var sidebar = $('#sidebar');
  
        if( sidebar.length > 0 ) {
            var button = $('.filter-toggle'); // applies for both "categories button" and "burger button"
            var siteOverlay = $('.site-overlay');
            var close = $('.close-sidebar');
            var tl = gsap.timeline( { paused: true, reversed: true } );

            tl.set(sidebar, { autoAlpha: 1 }).to(
                sidebar, .5, { x:0, ease: 'power4.inOut'}).to(
                    siteOverlay, .5, { autoAlpha: 1, ease: 'power4.inOut' }, "-=.5");
            button.on('click', function(e) {
                e.preventDefault();
                jQuery('html').css('overflow', 'hidden'); // disable background scrolling
                siteOverlay.addClass('active');
                siteOverlay.show();
                sidebar.show();
                tl.reversed() ? tl.play() : tl.reverse();
            });

            close.on('click', function(e) {
                e.preventDefault();
                tl.reverse();
                setTimeout( function() {
                    jQuery('html').css('overflow', 'auto'); // enable background scrolling
                    siteOverlay.removeClass('active');
                    sidebar.hide();
                }, 500);
            });
            
            siteOverlay.on('click', function(e) {
                e.preventDefault();
                tl.reverse();
                setTimeout( function() {
                    jQuery('html').css('overflow', 'auto'); // enable background scrolling
                    siteOverlay.removeClass('active');
                    sidebar.hide();
                }, 500);
            });
        }
    }

    $(document).ready(function() {

        // search widget: 

        // override default placeholder
        $('.site-sidebar .widget_product_search input').attr('placeholder', "חיפוש מוצרים...");

        // on mobile view - use the mobile-search-widget instead
        $('.site-sidebar .widget_product_search form').on('click', function(e) {
            if($(window).width() < 768) {
                e.preventDefault()

                // close sidebar
                $('.site-overlay').removeClass('active');
                $('#sidebar').hide();

                // open mobile-search-widget
                $('.mobile-nav-wrapper .search').addClass('active');
                $('.header-main .header-search').addClass('active');
                $('.mobile-nav-wrapper .search').css({ opacity: '1', visibility: 'visible'});
                setTimeout(function() { // wait in order to properly catch the widget
                    jQuery('.header-search .dgwt-wcas-search-input').focus(); // set focus on the search bar input
                }, 200);

                // NOTE: background scrolling is already disabled
            }
        });
        
        // filter-by-price widget: remove "מחיר" title (duplicated)
		$('.site-sidebar .widget_price_filter .price_label').html(function(_, html) {
        	return html.replace('מחיר: ', '');
    	});

        // add links from the default menu at the bottom of this custom menu (emulate the same structure)
        $('#insert_canvas_links_here').html(`
            <div class="site-canvas">
                <nav class="canvas-menu">
                    <ul class="menu">
                        ${$('.site-canvas .canvas-menu .menu').html()}
                    </ul>
                </nav>
            </div>
        `);
    });
})(jQuery);