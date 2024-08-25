<?php

add_action('wp_ajax_save_visitor_details', 'save_visitor_detail');
add_action('wp_ajax_nopriv_save_visitor_details', 'save_visitor_detail');
function save_visitor_detail()
{
    if (current_user_can('administrator')) {
        echo "User is admin";
    } else {
        
        $visitorId = $_POST['visitorId'];
        $email = $_POST['email'];
        $fetime = $_POST['feTime'];
        $etime = $_POST['eTime'];
        $ltime = $_POST['lTime'];
        $lat = $_POST['lat'];
        $lng = $_POST['lng'];
        $agent = $_POST['agent'];
        $pageStamp = $_POST['pageStamp'];
        $visitedStore = $_POST['visitedStore'];
        $action = $_POST['action'];
        $inStore = $_POST['inStore'];
        $inStoreId = $_POST['storeId'];
        $visits = '';
        $onlyETime = '';
        $onlyLTime = '';
        $duration = '';
        $timeArr1 = explode(' ', $fetime);
        $timeArr2 = explode(' ', $ltime);
        $onlyETime = $timeArr1[4];
        $onlyLTime = $timeArr2[4];
        // $location = "{'lat':".$lat.",'lng':".$lng."}";
        $location = array($lat, $lng);
        $location = serialize($location);
        // echo $visitorId." ".$email." ".$etime." ".$ltime." ".$lat." ".$lng." ".$agent." ".$pageStamp." ".$visitedStore." ".$action." ".$location;
        $pageStampStr = implode(" ", $pageStamp);

        global $wpdb;
        global $table_prefix;
        $table1 = $table_prefix . 'foottracking';
        $table2 = $table_prefix . 'storetracking';
        
        
        if ($inStore != false && $inStoreId != '') {
            
            $vendor = (get_wcmp_vendor($inStoreId));
            $vendorNameRough = $vendor->user_data->data->user_nicename;
            $vendorName = ucwords(str_replace('-', ' ', $vendorNameRough));
            die($vendor);
            $sql1 = "SELECT visits FROM $table2 WHERE store_name = '$vendorName' AND visitor_id = '$visitorId'";
            $getData = $wpdb->get_results($sql1);
            if ($wpdb->num_rows > 0) {
                $valueCount = $getData[0]->visits;
                if ($fetime != '' && $ltime != '') {
                    $visits = $valueCount + 1;

                    $secs = strtotime($onlyLTime) - strtotime("00:00:00");
                    $result = date("H:i:s", strtotime($onlyETime) + $secs);
                    $duration = $result;
                }
            } else {
                $valueCount = 0;
                if ($etime != '' && $ltime != '') {
                    $visits = $valueCount + 1;

                    $secs = strtotime($onlyLTime) - strtotime("00:00:00");
                    $result = date("H:i:s", strtotime($onlyETime) + $secs);
                    $duration = $result;
                }
            }
        }


        // Saving data to DB in 'foottracking' Table
        $result = $wpdb->insert($table1, [
            'visitor_id' => $visitorId,
            'email' => $email,
            'rawdata' => $agent,
            'pagestamp' => $pageStampStr,
            'location' => $location,
            'dateTime' => $etime,
            'LdateTime' => $ltime
        ]);
        if ($result == 1) {
            echo 'Data for foottracking Inserted Successfuly';
        } else {
            echo 'Error while Inserting data for foottracking';
        };
        // $ifVisitorDataExists = $wpdb->get_results("SELECT * FROM $table1 WHERE visitor_id = '" . $visitorId . "' AND dateTime = '$etime' AND LdateTime = '$ltime' ");

        // if ($wpdb->num_rows > 0) {
        // 	$result = $wpdb->update($table1, [
        // 		'email' => $email,
        // 		'rawdata' => $agent,
        // 		'pagestamp' => $pageStampStr,
        // 		'location' => $location,
        // 		// 'LdateTime' => $ltime
        // 	], ['visitor_id' => $visitorId, 'dateTime' => $etime]);
        // 	if ($result == 1) {
        // 		echo 'Data for foottracking Updated Successfuly';
        // 	} else {
        // 		echo 'Error while Updating data for foottracking';
        // 	};
        // 	// echo "Store data already available";
        // } else {
        // 	$result = $wpdb->insert($table1, [
        // 		'visitor_id' => $visitorId,
        // 		'email' => $email,
        // 		'rawdata' => $agent,
        // 		'pagestamp' => $pageStampStr,
        // 		'location' => $location,
        // 		'dateTime' => $etime,
        // 		'LdateTime' => $ltime
        // 	]);
        // 	if ($result == 1) {
        // 		echo 'Data for foottracking Inserted Successfuly';
        // 	} else {
        // 		echo 'Error while Inserting data for foottracking';
        // 	};
        // 	// echo "Store data is not available";
        // }

        // // Saving data to DB in 'storetracking' Table
        foreach ($visitedStore as $store) {
            $store = ucwords($store);

            $ifStoreExists = $wpdb->get_results("SELECT * FROM $table2 WHERE store_name = '" . $store . "' AND visitor_id = '$visitorId' ");

            if ($wpdb->num_rows > 0) {
                $result = $wpdb->update($table2, [
                    'email' => $email,
                    'visits' => $visits,
                    'time_duration' => $duration,
                ], ['store_name' => $store, 'visitor_id' => $visitorId]);
                if ($result == 1) {
                    echo 'Data for Store Updated Successfuly';
                } else {
                    echo 'Error while Updating data for Store';
                };
                // echo "Store data already available";
            } else {
                $result = $wpdb->insert($table2, [
                    'visitor_id' => $visitorId,
                    'email' => $email,
                    'store_name' => $store,
                    'visits' => $visits,
                    'time_duration' => $duration
                ]);
                if ($result == 1) {
                    echo 'Data for Store Inserted Successfuly';
                } else {
                    echo 'Error while Inserting data for Store';
                };
                // echo "Store data is not available";
            }
        }
    }

    wp_die();
}
