<?php

require_once 'mailer.php';
require_once 'incs/atlas.php';
require_once 'incs/maps.php';
require_once 'incs/captcha.php';


if($_SERVER['REQUEST_METHOD'] == "POST") {
    
    if(!verifyCaptcha()) {
        die("Error: Captcha can't be verified");
    }
    
    $name = filter_input(INPUT_POST, "firstName-moving");

    $lastName = filter_input(INPUT_POST, "lastName-moving");

    $city_from = trim($_POST["cityFrom-moving"]);

    $province_from = trim($_POST["provinceFrom-moving"]);

    $city_to = trim($_POST["cityTo-moving"]);

    $province_to = trim($_POST["provinceTo-moving"]);

    $number_of_rooms = trim($_POST["numberOfRooms"]);

    $date = trim($_POST["date-moving"]);

    $comments = trim($_POST["coments"]);

    $phone  = trim($_POST["phone-moving"]);

    $email  = filter_input(INPUT_POST, "email-moving", FILTER_SANITIZE_EMAIL);

    $pieces = trim($_POST["pieces-freight"]);


    // Set the recipient email address.

    // FIXME: Update this to your desired email address.
    $recipient = array("info@jaystransportationgroup.ca", "tayler@smedia.ca");

    // Set the email subject.
    $subject = "New contact from $name";

    // Build the email content.
    $email_content = "Basic information: \n\n";

    $email_content .= "First Name: $name \n";

    $email_content .= "Last Name: $lastName \n";

    $email_content .= "Email: $email \n";

    $email_content .= "Phone: $phone \n";

    $email_content .= "Company name: $company_name \n";

    $email_content .= "Address: $address \n";

    $email_content .= "City: $city \n";

    $email_content .= "Province: $province \n";

    $email_content .= "Zip: $zip \n";

    $email_content .= "State: $state \n";

    $email_content .= "Address destination: $address_destination \n";

    $email_content .= "City destination: $city_destination \n";

    $email_content .= "Province destination: $province_destination \n";

    $email_content .= "Zip destination: $zip_destination \n";

    $email_content .= "Pickup date: $pickup_date \n";

    $email_content .= "Delivery date: $delivery_date \n";

    $email_content .= "Freight: $freight \n";

    $email_content .= "Weight: $weight \n";

    $email_content .= "Pieces: $pieces \n";

    $email_content .= "Palette: $palette \n";

    $email_content .= "Stackable Palette: $stackablepalette \n";

    $email_content .= "Size: $size \n";



    // Build the email headers.
    $email_headers = "From: $name <$email>";

    $atlas_data = [
        'Bedrooms'                          => intval($number_of_rooms),
        'TotalWeight'                       => '0',
        'CustomerFirstName'                 => $name,
        'CustomerLastName'                  => $lastName,
        'CustomerHomePhone'                 => $phone,
        'CustomerWorkPhone'                 => '',
        'CustomerPrimaryEmail'              => $email,
        'OriginCity'                        => $city_from,
        'OriginState'                       => $province_from,
        'OriginPostalCode'                  => '',
        'OriginCountry'                     => 'Canada',
        'DestinationCity'                   => $city_to,
        'DestinationState'                  => $province_to,
        'DestinationPostalCode'             => '',
        'DestinationCountry'                => 'Canada',
        'RequestedLoadDate'                 => $date,
        'CustomerComment'                   => $comments,
        'LeadProviderSource'                => '',
        'ContactPreference'                 => '',
        'LeadProviderBestTimeToCall'        => '',
        'LeadProviderCallWork'              => ''
    ];

    $resp = submit2nearest($atlas_data);

    $message = $resp?"Synced with CRM" : "Not Synced";

    // Send the email.

    if (SendEmail($recipient, 'offers@mail.smedia.ca', $subject, $email_content, $email, $name) === true) {

        // Set a 200 (okay) response code.

        http_response_code(200);

        echo "Thank You! Your message has been sent. $message";

    } else {

        // Set a 500 (internal server error) response code.

        http_response_code(500);

        echo "Oops! Something went wrong and we couldn't send your message. $message";

    }

} else {

    // Not a POST request, set a 403 (forbidden) response code.

    http_response_code(403);

    echo "There was a problem with your submission, please try again.";

}