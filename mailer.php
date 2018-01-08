<?php

require_once 'PHPMailer/PHPMailerAutoload.php';

function SendEmail($tos, $from, $subject, $message, $reply_to = null, $reply_to_name = '', $smtp = array(), $content_type = 'text/plain', $SMTPDebug = 0)
{
    if(!is_array($tos)) { $tos = array($tos); }
    
    if(count($tos) === 0) { return "No reciepent"; }
    
    $default_smtp = array(
        'host'          => 'localhost',
        'port'          => 25,
        'auth_enabled'  => false,
        'auth_user'     => '',
        'auth_pass'     => '',
        'smtp_secure'   => false,
        'api_key'       => 'key-5d6a8ed775f4d239f0fcf3f8fbc07963',
        'domain'        => 'mail.smedia.ca'
    );
    
    if(!is_array($smtp)) { $smtp = array(); }
    
    $smtp2apply = array_merge($default_smtp, $smtp);
    
    $to = $tos[0];
    
    if(!is_array($to)) { $to = array($to, ''); }
    
    $_to = "{$to[1]}<{$to[0]}>";
    
    $_cc = '';
    
    for($i = 1; $i < count($tos); $i++) {
        $to = $tos[$i];
        if(!is_array($to)) { $to = array($to, ''); }
        if($_cc) { $_cc .= ','; }
        $_cc .= "{$to[1]}<{$to[0]}>";
    }
    
    if(!is_array($from)) { $from = array($from, ''); }
    
    $_from = "{$from[1]}<{$from[0]}>";
    
    if($reply_to && $reply_to_name) {
        $reply_to = "$reply_to_name<$reply_to>";
    }
    
    $response = sendThroughMailgun($smtp2apply['domain'], $smtp2apply['api_key'], $_from, $_to, $_cc, $subject, $message, $reply_to);

    if(isset($response->id)) {
        return true;
    } else {
        return "Error: $response->message";
    }
}

function sendThroughMailgun($domain, $api_key, $from, $to, $cc, $subject, $html, $reply_to = null)
{
    $sfrom = rawurlencode($from);
    $sto = rawurlencode($to);
    $scc = rawurlencode($cc);
    $ssubject = rawurlencode($subject);
    $shtml = rawurlencode($html);
    
    if($reply_to) {
        $reply_to = rawurlencode($reply_to);
    }
    
    $url = "https://api.mailgun.net/v3/$domain/messages";
    $post_data = "from=$sfrom&to=$sto&subject=$ssubject&html=$shtml";
    
    if($scc) {
        $post_data .= "&cc=$scc";
    }
    
    if($reply_to) {
        $post_data .= "&h:Reply-To=$reply_to";
    }
    
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_USERPWD, "api:$api_key");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_URL, str_replace('~', '%7E', $url));
    curl_setopt(
        $curl,
        CURLOPT_USERAGENT,
        'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:30.0) Gecko/20100101 Firefox/30.0'
    );
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/x-www-form-urlencoded"));
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
    
    $contents = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    
    if($curl)
    {
        curl_close($curl);
    }
    
    if ($httpcode>400) {
        return null;
    }

    if ($contents) {
        return json_decode($contents);
    } else {
        return null;
    }
}

function verifyCaptcha() {
    $api_url            = 'https://www.google.com/recaptcha/api/siteverify';
    $g_captcha_response = filter_input(INPUT_POST, 'g-recaptcha-response');
    $site_secret        = '6LdTazAUAAAAAGYjhZtrOUrHzzdqkhSzpR3iVnkq';
    
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

function prepareAdfXML($name, $email, $phone) {
    return '<?xml version="1.0" encoding="UTF-8"?>
    <?adf version="1.0"?>
    <adf>
        <prospect>
            <id sequence="' . time() . '" source="Prairie Auto Credit"></id>
            <requestdate>' . date('Y-m-dTG:i:s') . '</requestdate>

            <customer>
                <contact>
                     <name part="full">' . $name . '</name>
                     <email>' . $email . '</email>
                     <phone>' . $phone . '</phone>
                 </contact>
            </customer>

            <vendor>
                <contact>
                    <name part="full">Prairie Auto Credit</name>
                    <email>jeff@prairieautocredit.com</email>
                </contact>
            </vendor>
            <provider>
                <name part="full">sMedia</name>
                <url>http://smedia.ca</url>
                <email>offers@mail.smedia.ca</email>
                <phone>855-775-0062</phone>
            </provider>
        </prospect>
    </adf>';
}