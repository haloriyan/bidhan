<?php

namespace App\Framework;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/OAuth.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/POP3.php';

use PHPMailer\PHPMailer\PHPMailer as Mail;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\OAuth;

class Mailer {
    private static $_instance = null;
    public static $mail;
    public static $name;
    public static $email;
    public static $subject;
    public static $sender_mail;
    public static $sender_name;

    public function __construct() {
        global $isSendingMail;
        $isSendingMail = true;
        self::$mail = new Mail(true);
        self::$mail->isSMTP();
        self::$mail->Host = env('MAIL_HOST');
        self::$mail->SMTPAuth = true;
        self::$mail->Username = env('MAIL_USERNAME');
        self::$mail->Password = env('MAIL_PASSWORD');
        self::$mail->SMTPSecure = env('MAIL_ENCRYPTION');
        self::$mail->Port = env('MAIL_PORT');
    }
    public function to($email, $name) {
        self::$email = $email;
        self::$name = $name;
        if(self::$_instance === null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    public function from($sender_mail, $sender_name) {
        self::$sender_mail = $sender_mail;
        self::$sender_name = $sender_name;
        if(self::$_instance === null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    public function subject($subject) {
        self::$subject = $subject;
        if(self::$_instance === null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    public function send($view) {
        self::$mail->setFrom(self::$sender_mail, self::$sender_name);
        self::$mail->addAddress(self::$email, self::$name);

        self::$mail->IsHTML(true);
        self::$mail->Subject = self::$subject;
        self::$mail->Body = "$view";
        self::$mail->send();
        self::$mail->ClearAllRecipients();

        echo "message was sent";
    }
}
