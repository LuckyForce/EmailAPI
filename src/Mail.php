<?php

namespace LuckyForce\EmailAPI;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mail
{
    public function __construct()
    {
        //Check if every variable is set
        //Following variables are required:
        //$_POST['sender']
        //$_POST['email']
        //$_POST['recipient']
        //$_POST['subject']
        //$_POST['html-message']
        //$_POST['text-message']
        //$_POST['service']
        //$_POST['key']
        if (isset($_POST['sender']) && isset($_POST['email']) && isset($_POST['subject']) && isset($_POST['recipient']) && isset($_POST['html-message']) && isset($_POST['text-message']) && isset($_POST['service']) && isset($_POST['key'])) {
            //Set the variables
            $this->sender = $_POST['sender'];
            $this->email = $_POST['email'];
            $this->subject = $_POST['subject'];
            $this->recipient = $_POST['recipient'];
            $this->htmlMessage = $_POST['html-message'];
            $this->textMessage = $_POST['text-message'];
            $this->service = $_POST['service'];
            $this->key = $_POST['key'];

            //Read variables from .env file
            $file = file_get_contents('.env');
            $lines = explode("\n", $file);
            foreach ($lines as $line) {
                $line = explode('=', $line);
                $key = $line[0];
                $value = $line[1];
                //Get json encoded value
                $tmp = json_encode($value);
                //Cut of the last two characters of the value if there are any newlines
                if (substr(substr($tmp, -3),0,2) == "\\n" || substr(substr($tmp, -3),0,2) == "\\r") {
                    $value = substr($value, 0, -1);
                }
                $this->env[$key] = $value;
            }

            //Check if service is set in env
            if (isset($this->env[$this->service])) {
                //Check if requested service can be accessed with given key.
                if ($this->key === $this->env[$this->service]) {
                    //If yes, continue with the request.

                } else {
                    //Return error if key is wrong.
                    http_response_code(401);
                    echo "Key is wrong.";
                    exit;
                }
            } else {
                //Return error if service is not set in env.
                http_response_code(401);
                echo "Service for this Project is not provided.";
                exit;
            }
        } else {
            //Return Bad Request
            http_response_code(400);
            echo 'Bad Request';
            exit;
        }
    }

    public function sendMail()
    {
        try {
            //Server settings
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            //Create PHPMailer service
            $mail = new PHPMailer(true);
            //Debug on
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
            //Set the variables
            $mail->isSMTP();
            $mail->Host       = strval($this->env["MAIL_HOST"]);
            $mail->SMTPAuth   = true;
            $mail->Username   = strval($this->env["MAIL_USERNAME"]);
            $mail->Password   = strval($this->env["MAIL_PASSWORD"]);
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = intval($this->env["MAIL_PORT"]);
            $mail->CharSet   = 'UTF-8';
            $mail->Encoding  = 'base64';

            //Set the variables from the request
            $mail->setFrom(strval($this->env["MAIL_FROM_ADDRESS"]), strval($this->sender));
            $mail->addAddress(strval($this->email), strval($this->recipient));
            $mail->isHTML(true);
            $mail->Subject = strval($this->subject);
            $mail->Body    = strval($this->htmlMessage);
            $mail->AltBody = strval($this->textMessage);
            /*
            $mail->addAddress('ellen@example.com');               //Name is optional
            $mail->addReplyTo('info@example.com', 'Information');
            $mail->addCC('cc@example.com');
            $mail->addBCC('bcc@example.com');
        
            //Attachments
            $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
            */
            //Content
            //echo 'Message has been sent';
            //Send the mail
            $mail->send();

            //Return OK
            http_response_code(200);
            echo 'OK';
        } catch (Exception $e) {
            //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            http_response_code(500);
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
