<?php 
    header("Content-type:text/html;charset=utf-8");
     $street=$_GET["street"];
     $city=$_GET["city"];
     $state=$_GET["state"];
     $degree=$_GET["degree"];
$google_url="https://maps.googleapis.com/maps/api/geocode/xml?"."address=".$street.",".$city.",".$state."&"."key=AIzaSyANWWs3yKb-Eg2yQQjiklLxh0phP81UqI4";
        $xml = simplexml_load_file($google_url);
        $lat= $xml->result->geometry->location->lat;
        $lng= $xml->result->geometry->location->lng;
        if($degree == "Fahrenheit"){
           $units="us";
        }
        else if($degree=="Celsius")
        {
            $units="si";}
$forecast_url="https://api.forecast.io/forecast/a110e3588202611ca445c2075b93c326/".$lat.",".$lng."?units=".$units."&exclude=flags";
       
        //extract forecast message from json
        $contents = file_get_contents($forecast_url); 
        $results = json_decode($contents);
        $timezone= $results->{'timezone'};
        date_default_timezone_set($timezone);
    echo $contents;
?>