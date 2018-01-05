<?php

    require_once 'mailer.php';

    // Only process POST reqeusts.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the form fields and remove whitespace.
        $name = strip_tags(trim($_POST["firstName-freight"]));
        $name = str_replace(array("\r","\n"),array(" "," "),$name);
        $lastName = strip_tags(trim($_POST["lastName-freight"]));
        $lastName = str_replace(array("\r","\n"),array(" "," "),$lastName);
        $company_name = filter_var(trim($_POST["companyName-freight"]), FILTER_SANITIZE_EMAIL);
        $address = trim($_POST["address-freight"]);
        $city = trim($_POST["city-freight"]);
        $province = trim($_POST["province-freight"]);
        $zip = trim($_POST["zip-freight"]);
        $state = trim($_POST["state-freight"]);
        $address_destination = trim($_POST["addressDestination-freight"]);
        $city_destination  = trim($_POST["cityDestination-freight"]);
        $province_destination  = trim($_POST["provinceDestination-freight"]);
        $zip_destination  = trim($_POST["zipDestination-freight"]);
        $phone  = trim($_POST["phone-freight"]);
        $email  = trim($_POST["email-freight"]);
        $pickup_date  = trim($_POST["pickupDate-freight"]);
        $delivery_date  = trim($_POST["deliveryDate-freight"]);
        $freight  = trim($_POST["freight-freight"]);
        $weight  = trim($_POST["weight-freught"]);
        $pieces = trim($_POST["pieces-freight"]);
        $palette  = trim($_POST["palette-freight"]);
        $stackablepalette  = trim($_POST["stackablepalette-freight"]);
        $size  = trim($_POST["size-freight"]);

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