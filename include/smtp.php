<?php

use PHPMailer\PHPMailer\PHPMailer;

// MESSAGE & EMAIL CONFIGURATION FOR SCRIPT
class message{
    private $conn;

    public function send_mail($email, $message, $subject){
        $host = getenv('SMTP_HOST') ?: '#';
        $user = getenv('SMTP_USER') ?: '#';
        $pass = getenv('SMTP_PASS') ?: '#';
        $port = (int)(getenv('SMTP_PORT') ?: 465);
        $secure = getenv('SMTP_SECURE') ?: 'ssl';
        $fromEmail = getenv('SMTP_FROM') ?: (defined('WEB_EMAIL') ? WEB_EMAIL : 'contact@apxbank.com');
        $fromName  = defined('WEB_TITLE') ? WEB_TITLE : 'APX Bank';

        // Gracefully skip sending if SMTP host is placeholder or empty
        if ($host === '#' || empty($host) || $user === '#') {
            error_log("SMTP skipped: host or user not configured.");
            return false;
        }

        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = $host;
            $mail->SMTPAuth = true;
            $mail->Username = $user;
            $mail->Password = $pass;
            $mail->Port = $port;
            $mail->SMTPSecure = $secure;

            // Email Settings
            $mail->isHTML(true);
            $mail->setFrom($fromEmail, $fromName);
            $mail->addAddress($email);
            $mail->AddReplyTo($fromEmail, $fromName);
            $mail->Subject = $subject;
            $mail->MsgHTML($message);
            return $mail->Send();
        } catch (\Throwable $e) {
            error_log("SMTP send error: " . $e->getMessage());
            return false;
        }
    }
}
?>