// go over each 'policy.innerHTML' and replace every '\n' with '<br/>'
jQuery(document).ready(function($) {
    var policies = $('#tab-policies .mvx-product-policies');
    if(policies.length)
        for(policy of policies[0].children)
            policy.innerHTML = policy.innerHTML.split('\n').join('<br/>');
});

// get all instances of "store-info" and replace every '\n' with '<br/>'
jQuery(document).ready(function($) {
    var store_info = $('.store-info-toggle .description_data');    
    if(store_info.length)
        store_info.each(function() {
            this.innerHTML = this.innerHTML.split('\n').join('<br/>');
        });
});
