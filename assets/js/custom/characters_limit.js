/* set characters limit */
jQuery(document).ready(function($) {
    $('#billing_first_name').on('input', function() {
        if (this.value.length > 20)
            this.value = this.value.slice(0, 20);
    });
    $('#billing_last_name').on('input', function() {
        if (this.value.length > 20)
            this.value = this.value.slice(0, 20);
    });
    $('#billing_phone').on('input', function() {
        if (this.value.length > 10)
            this.value = this.value.slice(0, 10);
    });
    $('#billing_email').on('input', function() {
        if (this.value.length > 40)
            this.value = this.value.slice(0, 40);
    });
    $('#billing_company').on('input', function() {
        if (this.value.length > 20)
            this.value = this.value.slice(0, 20);
    });    
    $('#billing_postcode').on('input', function() { // represents street_number
        if (this.value.length > 4)
            this.value = this.value.slice(0, 4);
    });
    $('#billing_address_2').on('input', function() {
        if (this.value.length > 10)
            this.value = this.value.slice(0, 10);
    });
    $('#order_comments').on('input', function() {
        if (this.value.length > 200)
            this.value = this.value.slice(0, 200);
    });
});