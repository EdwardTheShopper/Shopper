<?php
/**
 * footer.php
 * @package WordPress
 * @subpackage Bacola
 * @since Bacola 1.0
 *
 */
?>
			</div><!-- homepage-content -->
		</div><!-- site-content -->
	</main><!-- site-primary -->

	<?php bacola_do_action('bacola_before_main_footer'); ?>

	<?php if (!function_exists('elementor_theme_do_location') || !elementor_theme_do_location('footer')) { ?>
	
		<?php
       /**
       * Hook: bacola_main_footer
       *
       * @hooked bacola_main_footer_function - 10
       */
       do_action('bacola_main_footer');

	    ?>
		
	<?php } ?>
	
	
	<?php bacola_do_action('bacola_after_main_footer'); ?>
	
	<div class="site-overlay"></div>
    <?php

   $vendors = get_mvx_vendors($args = array(), $return = 'id');
echo '<ul id="vendors-latlng">';
foreach ($vendors as $vendor_id) {
    $vendor_meta = get_user_meta($vendor_id);
    $store_lat = $vendor_meta['_store_lat'][0] ?? "";
    $store_lng = $vendor_meta['_store_lng'][0] ?? "";
}
echo '</ul>';

?>
	<?php wp_footer(); ?>
    <script src="https://maps.googleapis.com/maps/api/js?v=3&sensor=false&libraries=geometry&key=AIzaSyCBJJZNPd7bCU0XpvDdLvwpC8jBJrAsiyk"></script>
<script>

jQuery(document).ready(function(){
  var x = document.getElementById("demo");
  
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
  } else {
    x.innerHTML = "Geolocation is not supported by this browser.";
  }

  jQuery(document).ajaxStop(function(){
     if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
  } else {
    x.innerHTML = "Geolocation is not supported by this browser.";
  }
   });
});



function showPosition(position) {
  var x = document.getElementById("location");
  var latitude1 = position.coords.latitude;
  var longitude1 = position.coords.longitude;
  jQuery('#vendors-latlng li').each(function(i, li) {
  var latitude2 = jQuery(this).attr("data-lat");  
  var longitude2 = jQuery(this).attr("data-lng"); 
  var id = jQuery(this).attr("data-id"); 

  if(latitude2 !=='' || longitude2 !==''){
  var start = {lat: parseFloat(latitude1), lng: parseFloat(longitude1)};
  var destination = {lat: parseFloat(latitude2), lng: parseFloat(longitude2)}; 
  let directionsService = new google.maps.DirectionsService();
  let directionsRenderer = new google.maps.DirectionsRenderer();
  //directionsRenderer.setMap(map); // Existing map object displays directions
  // Create route from existing points used for markers
  const route = {
      origin: start,
      destination: destination,
      travelMode: 'DRIVING'
  }

  directionsService.route(route,
    function(response, status) { // anonymous function to capture directions
      
      if (status !== 'OK') {
        
        return;
      } else {
        directionsRenderer.setDirections(response); // Add route to the map
        var directionsData = response.routes[0].legs[0]; // Get data about the mapped route
        if (!directionsData) {
          window.alert('Directions request failed');
          return;
        }
        else {
          
           var distance = directionsData.distance.text + ' from you';
           var store_cls = 'dist-'+id;
  
    jQuery('.'+store_cls).html(distance);
         
        }
      }
    });

  
   
  }
   
  });
 
}

jQuery('body').on('click', '.quick-view-button', function() {
   setTimeout(function(){
       //alert('ok'); 
      if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
 window.scrollTo(0, 0);
}
else{
       jQuery('.mfp-wrap').scrollTop(0);
}
       //window.scrollTo(0, 0);
       //$(window).scrollTop( $("#topofthePage").offset().top );
       // jQuery("html, body").animate({ scrollTop: jQuery(".mfp-wrap")}, 1000);
     }, 1000);
});

jQuery('body').on('click', '.wcmp-report-abouse-wrapper .close', function() {
//jQuery(".wcmp-report-abouse-wrapper .close").on('click', function () {
       // jQuery(".wcmp-report-abouse-wrapper #report_abuse_form").slideToggle(500);
    });

    jQuery('body').on('click', ' #report_abuse', function() {
    //alert('ok');
    //jQuery('.wcmp-report-abouse-wrapper #report_abuse').on('click', function () {
        //jQuery(".wcmp-report-abouse-wrapper #report_abuse_form").slideToggle(1000);
    });
    

</script>
	</body>
</html>