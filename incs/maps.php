<?php

require_once __DIR__ . '/maps-config.php';
require_once __DIR__ . '/utils.php';

/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::                                                                         :*/
/*::  This routine calculates the distance between two points (given the     :*/
/*::  latitude/longitude of those points). It is being used to calculate     :*/
/*::  the distance between two locations using GeoDataSource(TM) Products    :*/
/*::                                                                         :*/
/*::  Definitions:                                                           :*/
/*::    South latitudes are negative, east longitudes are positive           :*/
/*::                                                                         :*/
/*::  Passed to function:                                                    :*/
/*::    lat1, lon1 = Latitude and Longitude of point 1 (in decimal degrees)  :*/
/*::    lat2, lon2 = Latitude and Longitude of point 2 (in decimal degrees)  :*/
/*::    unit = the unit you desire for results                               :*/
/*::           where: 'M' is statute miles (default)                         :*/
/*::                  'K' is kilometers                                      :*/
/*::                  'N' is nautical miles                                  :*/
/*::  Worldwide cities and other features databases with latitude longitude  :*/
/*::  are available at https://www.geodatasource.com                          :*/
/*::                                                                         :*/
/*::  For enquiries, please contact sales@geodatasource.com                  :*/
/*::                                                                         :*/
/*::  Official Web site: https://www.geodatasource.com                        :*/
/*::                                                                         :*/
/*::         GeoDataSource.com (C) All Rights Reserved 2017		   		     :*/
/*::                                                                         :*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
function distance($lat1, $lon1, $lat2, $lon2, $unit = 'K') {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);

    if ($unit == "K") {
        return ($miles * 1.609344);
    } else if ($unit == "N") {
        return ($miles * 0.8684);
    } else {
        return $miles;
    }
}

function getCityLocation($address, $country_code = 'CA') {
    global $maps_api_endpoint, $maps_api_key;
    if(!$address) { return null; }
    $url = "{$maps_api_endpoint}&key={$maps_api_key}&address=" . rawurlencode($address);
    $json = HttpGet($url);
    if(!$json) { return null; }
    $obj = json_decode($json, true);
    if(!$obj) { return null; }
    if($obj['status'] != 'OK') { return null; }
    if(count($obj['results']) == 0) { return null; }
    $location = $obj['results'][0];
    $component_count = 0;
    foreach($location['address_components'] as $component) {
        $component_count++;
        if($component['types'][0] == 'country') { break; }
    }
    
    $first_component = $location['address_components'][0];
    $last_component = $location['address_components'][$component_count - 1];
    
    if($last_component['types'][0] != 'country' || $last_component['short_name'] != $country_code) { return null; }
    if($first_component['types'][0] != 'locality') { return null; }
    
    return [
        'name'          => $first_component['long_name'],
        'state'         => $location['address_components'][$component_count - 2]['long_name'],
        'state_code'    => $location['address_components'][$component_count - 2]['short_name'],
        'location'      => $location['geometry']['location']
    ];
}

function getNearestLocation($locations, $city_location) {
    
    $distances = [];
    
    foreach($locations as $name => $location) {
        $distances[$name] = distance($location['lat'], $location['lng'], $city_location['lat'], $city_location['lng']);
    }
    
    asort($distances, SORT_NUMERIC);
    
    $cities = array_keys($distances);
    
    return $cities[0];
}