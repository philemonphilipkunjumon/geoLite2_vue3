<?php
require_once 'vendor/autoload.php';
use GeoIp2\Database\Reader;
$request_body = file_get_contents('php://input');
$data = json_decode($request_body, true);

$cityReader = new Reader('../db/GeoLite2-City.mmdb');
$exceptionArray=[];
$responseArray=[];
foreach ($data['data'] as  $index => $ip) {
    try{
        $record = $cityReader->city($ip);
        $responseArray[$index]=[
            'ip' =>$ip,
            'country_code' =>$record->country->isoCode,
            'postal_code' =>$record->postal->code,
            'city_name' =>$record->city->name,
            'time_zone' =>$record->location->timeZone,
            'accuracy_radius'=>$record->location->accuracyRadius
        ];
    } catch (Exception $exception) {
        $exceptionArray[$index]=$exception->getMessage();
        $responseArray[$index]=[
            'ip' =>$ip,
            'country_code' =>'Not Available in Database',
            'postal_code' =>'Not Available in Database',
            'city_name' =>'Not Available in Database',
            'time_zone' =>'Not Available in Database',
            'accuracy_radius'=>'Not Available in Database',
        ];
    }

}
print_r(json_encode(["data"=>$responseArray,'exception'=>$exceptionArray],true));
exit;
?>