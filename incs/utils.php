<?php

/*
 * @summary     : Make http get request using CURL
 * @param url   : The url to make the GET request to
 * @return      : string data obtained through the get request
 **************************************************************************/
function HttpGet($url)
{
    $curl = curl_init();
    
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_URL, str_replace('~', '%7E', $url));
    curl_setopt( $curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:30.0) Gecko/20100101 Firefox/30.0');

    $contents = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    
    if($curl) { curl_close($curl); }
    if ($httpcode > 400) { return null; }
    if ($contents) { return $contents; } else { return null; }
}

/**
 * @summary     : Make http post request using CURL
 * @param url   : The url to make the GET request to
 * @return      : string data obtained through the get request
 **************************************************************************/
function HttpPost($url, $post_data, $content_type = 'application/x-www-form-urlencoded')
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_URL, str_replace('~', '%7E', $url));
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:30.0) Gecko/20100101 Firefox/30.0');
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_AUTOREFERER, true);
    curl_setopt($curl, CURLOPT_HEADER, true);
    curl_setopt($curl, CURLINFO_HEADER_OUT, true);
    curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: $content_type"));
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($curl, CURLOPT_REFERER, $url);
    
    $contents = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $header  = curl_getinfo($curl);
    $header_content = substr($contents, 0, $header['header_size']);
    $body_content = trim(str_replace($header_content, '', $contents));

    if($curl) { curl_close($curl); }
    if ($httpcode>400) { return null; }
    if ($contents) { return $body_content; } else { return null; }
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}