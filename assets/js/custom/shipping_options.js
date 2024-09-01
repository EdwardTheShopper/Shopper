/* on every city selection - get the available shipping options according to the chosen city,
   then, populate 'delivery_rules' with those options */
jQuery(document).ready(function($) {
    $('body').on('change', '#billing_city', function() {
        var chosen_city = $(this).val().trim(); // use trim() in order to match the fields on ACF

        if(chosen_city) {
            // clear previous options
            $('#delivery_rules_field .woocommerce-input-wrapper input[name="delivery_rules"]').remove();
            $('#delivery_rules_field .woocommerce-input-wrapper label').remove();
            $('#delivery_rules_field .woocommerce-input-wrapper').text('');

            // set loading indicator
            $('#delivery_rules_field .woocommerce-input-wrapper').append('טוען..');

            $.ajax({
                url: custom_checkout_params.ajax_url,
                type: 'GET',
                data: {
                    action: 'get_shipping_options',
                    chosen_city: chosen_city
                },
                success: function(response) {
                    if(response.success) {
                        var available_options = response.data;
                        
                        // clear previous options
                        $('#delivery_rules_field .woocommerce-input-wrapper input[name="delivery_rules"]').remove();
                        $('#delivery_rules_field .woocommerce-input-wrapper label').remove();
                        $('#delivery_rules_field .woocommerce-input-wrapper').text('');

                        // set 'available_options' as options to 'delivery_rules'
                        // (adding a wrapper div for better design)
                        $.each(available_options, function(index, value) {
                            $('#delivery_rules_field .woocommerce-input-wrapper').append(`
                                <div id="delivery_rules_${index}_wrapper" style="display: flex">
                                    <input type="radio" class="input-radio" name="delivery_rules" value="${index}" id="delivery_rules_${index}">
                                    <label for="delivery_rules_${index}" class="radio">
                                        ${value[0]}
                                    </label>
                                    <div id="shipping_cost_${index}" style="display: none;" 
                                        value="${!value[1]? 0 : value[1]}" />
                                <div/>`
                            );
                        });
                    } else { // no match to delivery rules
                        // clear previous options
                        $('#delivery_rules_field .woocommerce-input-wrapper input[name="delivery_rules"]').remove();
                        $('#delivery_rules_field .woocommerce-input-wrapper label').remove();
                        $('#delivery_rules_field .woocommerce-input-wrapper').text('');
                        
                        // set default message
                        $('#delivery_rules_field .woocommerce-input-wrapper').append(`
                            <div style="display: flex; flex-direction: column;">${response.data}</div>`);
                    }
                },
                error: function(xhr, status, error) {
                    // clear previous options
                    $('#delivery_rules_field .woocommerce-input-wrapper input[name="delivery_rules"]').remove();
                    $('#delivery_rules_field .woocommerce-input-wrapper label').remove();
                    $('#delivery_rules_field .woocommerce-input-wrapper').text('');

                    // set error message
                    $('#delivery_rules_field .woocommerce-input-wrapper').append(
                        'אירעה שגיאה, יש לרענן את הדף ולנסות שוב'
                    );
                    console.error('AJAX Error:', error);
                }
            });
        }    
    });
});

/* on every shipping option selection - add the shipping cost to the cart and re-calculate the total amount */
jQuery(document).ready(function($) {
    $('#delivery_rules_field .woocommerce-input-wrapper').change(function() {
        var chosen_delivery = $('input[name="delivery_rules"]:checked').val(); // value is 0, 1 or 2
        var shipping_cost = parseFloat($('#shipping_cost_' + chosen_delivery).attr('value'));

        // remove previous shipping cost from the cart (if exists)
        $('tr.fee').remove(); // do it first for better display
        $('tr.shipping').remove();
        
        // add the current shipping cost to the cart
        $.ajax({
            type: 'POST',
            url: wc_checkout_params.ajax_url,
            data: {
                action: 'add_shipping_cost_to_cart',
                shipping_cost: shipping_cost
            },
            success: function(response) {
                if(response.success)
                    $(document.body).trigger('update_checkout');
            }
        })
        .then(()=> {
            if(shipping_cost === 0) // add a display of free delivery
                $('tr.cart-subtotal').after(`
                    <tr class="cart_item shipping">
                    <td class="product-name" style="text-align: start">משלוח</td>
                    <td class="product-total">
                        <span class="woocommerce-Price-amount amount">
                            <bdi>
                                <span class="woocommerce-Price-currencySymbol">&#8362;</span>
                                0.00
                            </bdi>
                        </span>
                    </td>
                </tr>
            `)
        });
    });
});