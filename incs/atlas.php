<?php

require_once __DIR__ . '/atlas-config.php';
require_once __DIR__ . '/utils.php';
require_once __DIR__ . '/maps.php';

function submit2atlas($atlas_account, $atlas_data) {
    global $atlas_endpoint, $atlas_content_type;
    
    $atlas_data['GeneratingSourceTrackingNumber']   = date('dmYHis');
    $atlas_data['LeadProviderReceivedDate']         = date('Y-m-d');
    
    $post_data = json_encode(atlas_clean_data(array_merge($atlas_account, $atlas_data)));
    
    $response = HttpPost($atlas_endpoint, $post_data, $atlas_content_type);
    
    return stripos($response, '<Received>true</Received>') > 0 || stripos($response, '"Received":true') > 0;
}

function atlas_clean_data($data) {
    global $atlas_default;
    return array_filter(array_intersect_key($data, $atlas_default));
}

function submit2nearest($atlas_data) {
    global $atlas_accounts, $atlas_location_map, $maps_locations;
    
    $location = getCityLocation("{$atlas_data['OriginCity']}, {$atlas_data['OriginState']}");
    
    if(!$location) { echo "Location for {$atlas_data['OriginCity']}, {$atlas_data['OriginState']} is Null"; return null; }
    
    $nearest = getNearestLocation($maps_locations, $location['location']);
    
    $atlas_data['CustomerComment'] = "{$atlas_data['CustomerComment']}\nNearest Jay's Location: $nearest";
    
    $atlas_account_name = $atlas_location_map[$nearest];
    
    return submit2atlas($atlas_accounts[$atlas_account_name], $atlas_data);
}
