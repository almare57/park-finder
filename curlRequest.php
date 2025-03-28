<?php
    function curlRequest($path_with_parameters){
        //setup curl
        $api_key = getenv('API_KEY');
        $url = "https://developer.nps.gov/api/v1/".$path_with_parameters;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $headers = [
            'X-Api-Key: '.$api_key,
            'Accept: application/json',
        ];
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        //execute request
        $result = curl_exec($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        $data = new stdClass();
        $result = json_decode($result);

        if($http_status == 429){
            //rate limit is 1000 per hour. Will respond with code 429
            $message = "You have reached the rate limit. Try again later.";
        }
        else{
            if(isset($result->data)){
                $data = $result->data;
                $message = "Success";
            }
            else{
                $message = "Something went wrong";
            }
        }

        curl_close($curl);

        return array("status" => $http_status, "data" => $data, "message" => $message);
    }