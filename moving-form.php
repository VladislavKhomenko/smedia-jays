<?php

    require_once 'mailer.php';

    // Only process POST reqeusts.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //Get the form fields and remove whitespace.
        $name = strip_tags(trim($_POST["firstName-moving"]));
        $name = str_replace(array("\r","\n"),array(" "," "),$name);
        $lastName = strip_tags(trim($_POST["lastName-moving"]));
        $lastName = str_replace(array("\r","\n"),array(" "," "),$lastName);
        $city_from = trim($_POST["cityFrom-moving"]);
        $province_from = trim($_POST["provinceFrom-moving"]);
        $city_to = trim($_POST["cityTo-moving"]);
        $province_to = trim($_POST["provinceTo-moving"]);
        $number_of_rooms = trim($_POST["numberOfRooms"]);
        $date = trim($_POST["date-moving"]);
        $comments = trim($_POST["coments"]);
        $phone  = trim($_POST["phone-moving"]);
        $email  = trim($_POST["email-moving"]);
        $pieces = trim($_POST["pieces-freight"]);

        // Set the recipient email address.
        // FIXME: Update this to your desired email address.
        $recipient = array("konstantinkudelko@mail.ru");

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

        // Send the email.
        if (SendEmail($recipient, 'offers@mail.smedia.ca', $subject, $email_content, $email, $name) === true) {
            // Set a 200 (okay) response code.
            http_response_code(200);
            echo "Thank You! Your message has been sent.";
        } else {
            // Set a 500 (internal server error) response code.
            http_response_code(500);
            echo "Oops! Something went wrong and we couldn't send your message.";
        }

    } else {
        // Not a POST request, set a 403 (forbidden) response code.
        http_response_code(403);
        echo "There was a problem with your submission, please try again.";
    }