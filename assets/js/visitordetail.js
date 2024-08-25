/* 
* Move code from includes/header-type1.php for user details
*/

localStorage.removeItem('state-unload');
localStorage.removeItem('state-load');

var ajaxUrl = '/wp-admin/admin-ajax.php';

// Function for Saving all data to Database
function savetoDatabase() {
    visitorDetails = JSON.parse(localStorage.getItem('visitorDetails'));
    jQuery.post(ajaxUrl, visitorDetails, function(response, status) {
        if (status != 'success') {
            alert('Failed to save visitor data')
        } else if (status == 'success') {
            console.log("yes :" . response)
        }
    });
}

if (!localStorage.getItem('visitorDetails')) {
    visitorDetails = {
        'visitorId': '',
        'email': '',
        'feTime': new Date(Date.now()).toString().split('GMT')[0],
        'eTime': '',
        'lTime': '',
        'lat': '',
        'lng': '',
        'pageStamp': [],
        'visitedStore': [],
        'agent': '',

        'action': 'save_visitor_details',
        'inStore': false,
        'storeId': '',
        'firstVisit': true,
        'popupClosed': 0,
    }

    localStorage.setItem('visitorDetails', JSON.stringify(visitorDetails));
}


jQuery(document).ready(function() {
    
    var vendorStoreLatLng = '';
        var locationFetched;
        var visitorDetails = JSON.parse(localStorage.getItem('visitorDetails'));

        // Generating and Saving Visitor ID
        if (visitorDetails.visitorId == '') {
            function randomFixedInteger(length) {
                return Math.floor(Math.pow(10, length - 1) + Math.random() * (Math.pow(10, length) - Math.pow(10, length - 1) - 1));
            }
            var vid = randomFixedInteger(10);
            visitorDetails.visitorId = vid
            localStorage.setItem('visitorDetails', JSON.stringify(visitorDetails));
        }

        // Saving Enter Time
        if (visitorDetails.eTime == '') {
            visitorDetails.eTime = new Date(Date.now()).toString().split('GMT')[0]
            localStorage.setItem('visitorDetails', JSON.stringify(visitorDetails));
        }

        // Saving UserAgent
        var userAgent = navigator.userAgent;
        visitorDetails.agent = userAgent;
        localStorage.setItem('visitorDetails', JSON.stringify(visitorDetails));

        // Saving Page Stamps
        var pageUrl = jQuery(location).attr('href');
        if (pageUrl.includes('wp-admin') || pageUrl.includes('wp-login')) {

        } else {
            if (!visitorDetails.pageStamp.includes(pageUrl)) {
                visitorDetails.pageStamp.push(pageUrl);
                localStorage.setItem('visitorDetails', JSON.stringify(visitorDetails));
            }
        }

        // Saving the Store name if visitor visits any Store
        var link = jQuery(location).attr('href');
        if (link.includes('store')) {
            var x = link.split('/');
            var y = x.indexOf('store');
            var storeName = x[y + 1].replace(/-/g, ' ');

            if (!visitorDetails.visitedStore.includes(storeName)) {
                visitorDetails.visitedStore.push(storeName);
                localStorage.setItem('visitorDetails', JSON.stringify(visitorDetails));
            }
        }

        // Saving visitor Location lat/lng
        const success = function(position) {
            console.log(position)
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;

            visitorDetails.lat = lat;
            visitorDetails.lng = lng;
            localStorage.setItem('visitorDetails', JSON.stringify(visitorDetails));

            locationFetched = true;
            // savetoDatabase();
        }
        const error = function(error) {
            console.log(error);
            locationFetched = false;
        }
        navigator.geolocation.watchPosition(success, error, {
            enableHighAccuracy: true
        });

        // Gettig all Stores lat/lng and do action
        if (visitorDetails.lat != '' && visitorDetails.lng != '') {
            data = {
                action: 'get_vendors_lat_lng',
            }

            jQuery.post(ajaxUrl, data, function(response, status) {
                vendorStoreLatLng = JSON.parse(response);
                jQuery.each(vendorStoreLatLng, function(i, val) {
                    storePoint = {
                        lat: val[0],
                        lng: val[1]
                    }

                    visitorPoint = {
                        lat: visitorDetails.lat,
                        lng: visitorDetails.lng
                    }

                    function arePointsNear(checkPoint, centerPoint, km) {
                        var ky = 40000 / 360;
                        var kx = Math.cos(Math.PI * centerPoint.lat / 180.0) * ky;
                        var dx = Math.abs(centerPoint.lng - checkPoint.lng) * kx;
                        var dy = Math.abs(centerPoint.lat - checkPoint.lat) * ky;
                        return Math.sqrt(dx * dx + dy * dy) <= km;
                    }

                    var n = arePointsNear(visitorPoint, storePoint, 0.1);

                    if (n == true) {
                        visitorDetails.inStore = true;
                        visitorDetails.storeId = i;
                        localStorage.setItem('visitorDetails', JSON.stringify(visitorDetails));
                        console.log('Is in Store');
                    } else {
                        console.log('Not in Store');
                    }
                });
            });
        }



 // Function to check if Location is fully fetched
 function watchVariable(oldvalue) {
    clearcheck = setInterval(repeatcheck, 500, oldvalue);

    function repeatcheck(oldvalue) {
        console.log('Fetching Location...');
        if (locationFetched !== oldvalue) {
            // do something
            clearInterval(clearcheck);
            console.log('Location Fetched.');

            // saving data to database
            savetoDatabase();
            console.log('Data Saved to Database.');
        }
    }
}
watchVariable(locationFetched);

});