<?php

require_once 'mailer.php';
require_once 'incs/captcha.php';

if(!verifyCaptcha()) {
    die('Error: Unable to verify captcha');
}

$recepient = array(/*'konstantinkudelko@mail.ru', */'info@jaystransportationgroup.ca', "tayler@smedia.ca");
//$special_recepient = ['konstantinkudelko@mail.ru'];
$sitename = "Jay's Transportation Group";

$name = trim(filter_input(INPUT_POST, "name"));
$email = trim(filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL));
$subject = trim(filter_input(INPUT_POST, "subject"));
$text = trim(filter_input(INPUT_POST, "message"));

$pagetitle = "New sign from \"$sitename\"";
$message = "Name: $name \Subject: $subject \n Email: $email \n Message: $text";


if($name && $subject && $email && $text) {
    echo SendEmail($recepient, 'offers@mail.smedia.ca', $pagetitle, $message, $email, $name);
    //echo SendEmail($special_recepient, 'offers@mail.smedia.ca', $pagetitle, prepareAdfXML($name, $email, $subject), $email, $name);
} else {
    echo "Error: Missing required fields";
}
