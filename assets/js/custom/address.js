/* on every city selection - fetching a list of streets according to the chosen city,
   then, populate 'billing_address_1' with options from the fetched list */
jQuery(document).ready(function($) {
    $('body').on('change', '#billing_city', function() {
        var chosen_city = $(this).val(); // DON'T use trim() in order to match the original API fields

        if(chosen_city) {
            $('#billing_address_1').empty(); // clear previous options
            $('#billing_address_1').append($('<option>', { value: 1, text:
                'טוען..'}) // set loading indicator
            );

            $.ajax({
                url: custom_checkout_params.ajax_url,
                type: 'GET',
                data: {
                    action: 'get_streets',
                    chosen_city: chosen_city
                },
                success: function(response) {
                    if(response.success) {
                        var streets = response.data;
                        $('#billing_address_1').empty(); // clear previous options
                        $.each(streets, function(key, value) { // set the list as options to 'billing_address_1'
                            $('#billing_address_1').append($('<option>', {
                                value: key,
                                text: value
                            }));
                        });
                    } else {
                        $('#billing_address_1').empty(); // clear previous options
                        $('#billing_address_1').append($('<option>', { value: 1, text:
                            'אירעה שגיאה, יש לרענן את הדף ולנסות שוב'})
                        );
                        console.error('Failed to fetch streets: ' + response.data);
                    }
                },
                error: function(xhr, status, error) {
                    $('#billing_address_1').empty(); // clear previous options
                    $('#billing_address_1').append($('<option>', { value: 1, text:
                        'אירעה שגיאה, יש לרענן את הדף ולנסות שוב'})
                    );
                    console.error('AJAX Error:', error);
                }
            });
        }
    });
});