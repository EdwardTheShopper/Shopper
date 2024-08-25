jQuery(document).ready(function($) {
    $('.dgwt-wcas-search-form').on('submit', function(e) {
        e.preventDefault();
        var searchQuery = $(this).find('.dgwt-wcas-sf-wrapp input[type="search"]').val();
        var currentUrl = window.location.pathname;

        // Extract the dynamic part of the URL, assuming it is after /store/
        var dynamicPath = currentUrl.split('/store/')[1];

        if (dynamicPath) {
            var basePath = '/store/' + dynamicPath.split('/')[0];
            var newAction = basePath + '/?s=' + encodeURIComponent(searchQuery);

            // Redirect to the new action
            window.location.href = newAction;
        }
    });
});
