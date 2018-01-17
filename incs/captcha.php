<?php

require_once __DIR__ . '/captcha-config.php';

function verifyCaptcha() {
    $api_url            = 'https://www.google.com/recaptcha/api/siteverify';
    $g_captcha_response = filter_input(INPUT_POST, 'g-recaptcha-response');
    $site_secret        = recaptcha_secret;
    
    if(!$g_captcha_response) { return false; }
    
    $post_data = "secret=$site_secret&response=$g_captcha_response";
    
    try {
        
        $response = HttpPost($api_url, $post_data);
        
        if(!$response) { return false; }
        
        $resp_obj = json_decode($response);
        
        return $resp_obj? $resp_obj->success : false;
        
    } catch (Exception $ex) {
        return false;
    }
}