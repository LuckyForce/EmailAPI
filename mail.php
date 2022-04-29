<?php

namespace EmailAPI;

use PHPMailer\PHPMailer\PHPMailer;

class Mail
{
    public function __construct()
    {
        //Read variables from .env file
        $file = file_get_contents('.env');
        $lines = explode("\n", $file);
        foreach ($lines as $line) {
            $line = explode('=', $line);
            $key = $line[0];
            $value = $line[1];
            $this->env[$key] = $value;
        }
        //Check if requested service can be accessed with given key.
        $service = $_POST['service'];
        $key = $_POST['key'];
        if ($key === $this->env[$service]) {
            //If yes, continue with the request.
            //Create PHPMailer service
            $this->mail = new PHPMailer(true);
            //Set the variables
            $this->mail->isSMTP();
            $this->mail->Host       = $this->env["MAIL_HOST"];
            $this->mail->SMTPAuth   = true;
            $this->mail->Username   = $this->env["MAIL_USERNAME"];
            $this->mail->Password   = $this->env["MAIL_PASSWORD"];
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $this->mail->Port       = $this->env["MAIL_PORT"];
            $this->mail->CharSet   = 'UTF-8';
            $this->mail->Encoding  = 'base64';
        } else {
            //If the key is incorrect, redirect to get.php
            echo '<script type="module">window.location.href = "get.php";</script>';
            //Exit the script
            exit();
        }
    }

    public function sendMail()
    {
        //Send Mail
        //Check if is from is set
        if ($this->from !== null) {
            //If yes, set the from
            $this->mail->setFrom($this->env["MAIL_FROM_ADDRESS"], $this->from);
        } else {
            //If not, set the from to the default
            $this->mail->setFrom($this->env["MAIL_FROM_ADDRESS"]);
        }
    }

    public function setFrom($from)
    {
        $this->from = $from;
    }

    public function setTo($to)
    {
        $this->to = $to;
    }

    public function setHTMLFooter($footer)
    {
        $this->footer = $footer;
    }

    public function setHTMLHeader()
    {
        return $this->header;
    }

    public function setFooter()
    {
        return $this->footer;
    }

    public function setHeader()
    {
        return $this->header;
    }
}
