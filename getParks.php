<?php
    include("curlRequest.php");
    header('Access-Control-Allow-Methods: GET');

    $activity_id = $_GET["activity_id"];

    if($activity_id && $activity_id != "") {
        $response = curlRequest("activities/parks?id=" . $activity_id);
        if($response["status"] && !empty((array)$response["status"])) {
            //pass back request data from curl request method
            http_response_code($response["status"]);
            echo json_encode($response);
        }
        else{
            //expecting the status to be passed back from the curl request method
            http_response_code(500);
        }
    }
    else{
        //frontend did not provide activity id
        $http_status = 400;
        http_response_code($http_status);
        echo json_encode(array("status" => $http_status, "data" => new stdClass(), "message" => "You must provide an activity id"));
    }
?>