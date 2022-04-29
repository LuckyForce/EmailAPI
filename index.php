<?php
//Author: Adrian Schauer
//Date: 2022-04-29

namespace EmailAPI;

use EmailAPI\Mail;

//Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //Create a new Post object
    $post = new Mail();
    //Set the variables
    
    //Send the mail
    $post->sendMail();
}else{
    //If not redirect to get.php
    echo '<script type="module">window.location.href = "get.php";</script>';
}
