<?php
//Author: Adrian Schauer
//Date: 2022-04-29
require_once '../vendor/autoload.php';

include 'Mail.php';

use LuckyForce\EmailAPI\Mail;

//Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //Create a new Post object
    $mail = new Mail();
    //Send the mail
    $mail->sendMail();
}else{
    //If not redirect to get.php
    echo require_once 'get.php';
}
