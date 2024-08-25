window.onload = function () {

    var slug = custom_script_vars.slug;
    var flag = custom_script_vars.flag;

    if (flag) {
        console.log("Flag = " + flag);
        let siteBrandDivs = document.querySelectorAll('.site-brand');

        // Loop through each div
        siteBrandDivs.forEach(siteBrandDiv => {
            // Find all <a> tags within the current div
            let anchorTags = siteBrandDiv.querySelectorAll('a');

            // Loop through and remove each <a> tag
            anchorTags.forEach(anchorTag => {
                siteBrandDiv.removeChild(anchorTag);
            });
        });
    }
    else {

        var logoLink = document.querySelectorAll('.site-brand a');
        var breadCrumb = document.querySelector('.woocommerce-breadcrumb ul li a');
        logoLink.forEach(function (link) {
            link.href = slug;
        });
        breadCrumb.href = slug;
    }
};