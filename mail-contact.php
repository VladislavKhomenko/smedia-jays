<?php

require_once 'mailer.php';

$recepient = array('konstantinkudelko@mail.ru', '14seldor14@mail.ru');
$special_recepient = ['konstantinkudelko@mail.ru'];
$sitename = "Jay's Transportation Group";

$name = trim(filter_input(INPUT_POST, "name-contact"));
$email = trim(filter_input(INPUT_POST, "email-contact", FILTER_SANITIZE_EMAIL));
$subject = trim(filter_input(INPUT_POST, "subject-contact"));
$text = trim(filter_input(INPUT_POST, "message-contact"));

$pagetitle = "New sign from \"$sitename\"";
$message = "Name: $name \Subject: $subject \n Email: $email \n Message: $text";


if($name && $subject && $email && $text) {
    echo SendEmail($recepient, 'offers@mail.smedia.ca', $pagetitle, $message, $email, $name);
    echo SendEmail($special_recepient, 'offers@mail.smedia.ca', $pagetitle, prepareAdfXML($name, $email, $subject), $email, $name);
} else {
    echo "Missing required fields";
}
