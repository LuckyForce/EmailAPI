<?php
//Author: Adrian Schauer
//Date: 2022-04-29
require_once '../vendor/autoload.php';

require_once 'Mail.php';

use LuckyForce\EmailAPI\Mail;

//Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "Request method is POST.";
    //Create a new Post object
    $mail = new Mail();
    //Send the mail
    $mail->sendMail();
}else{
    //If not redirect to get.php
    echo "This API Service only accepts POST requests.";
}
