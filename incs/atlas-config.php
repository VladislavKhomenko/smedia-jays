<?php

$atlas_endpoint      = "https://sslws-qa.atlasworldgroup.com/AtlasLeadReceiverWcf/LeadReceiver.svc/SubmitLeadPost";
$atlas_content_type  = "application/json";

$atlas_accounts     = [
    'AB'  => [
        'LeadProvider'  => 'JaysMovingAB',
        'UserName'      => 'jaysab',
        'Password'      => 'UbGfncG2',
        'AtlasAgentID'  => '8423'
    ],
    'SK'  => [
        'LeadProvider'  => 'JaysMovingSK',
        'UserName'      => 'jayssk',
        'Password'      => '6hXNYhj3',
        'AtlasAgentID'  => '8410'
    ]
];

$atlas_location_map = [
    'Battleford'        => 'SK',
    'Calgary'           => 'AB',
    'Regina'            => 'SK',
    'Saskatoon'         => 'SK',
    'Yorkton'           => 'SK',
    'Swift Current'     => 'SK',
    'Moose Jaw'         => 'SK',
    'Prince Albert'     => 'SK',
    'Estevan'           => 'SK'
];

$atlas_default = [
    'LeadProvider'                      => '',
    'UserName'                          => '',
    'Password'                          => '',
    'AtlasAgentID'                      => '',
    'GeneratingSourceTrackingNumber'    => '',
    'Bedrooms'                          => '',
    'TotalWeight'                       => '',
    'CustomerFirstName'                 => '',
    'CustomerLastName'                  => '',
    'CustomerHomePhone'                 => '',
    'CustomerWorkPhone'                 => '',
    'CustomerPrimaryEmail'              => '',
    'OriginCity'                        => '',
    'OriginState'                       => '',
    'OriginPostalCode'                  => '',
    'OriginCountry'                     => '',
    'DestinationCity'                   => '',
    'DestinationState'                  => '',
    'DestinationPostalCode'             => '',
    'DestinationCountry'                => '',
    'RequestedLoadDate'                 => '',
    'CustomerComment'                   => '',
    'LeadProviderReceivedDate'          => '',
    'LeadProviderSource'                => '',
    'ContactPreference'                 => '',
    'LeadProviderBestTimeToCall'        => '',
    'LeadProviderCallWork'              => ''
];