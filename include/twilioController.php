<?php
require_once __DIR__ . "/vendor/autoload.php";
use Twilio\Rest\Client;

class twilioController
{

    public static function sendSmsCode($number,$message_code){

        $sid    = getenv("TWILIO_ACCOUNT_SID") ?: "YOUR_TWILIO_ACCOUNT_SID";
        $token  = getenv("TWILIO_AUTH_TOKEN") ?: "YOUR_TWILIO_AUTH_TOKEN";
        $service_sid = getenv("TWILIO_SERVICE_SID") ?: "YOUR_MESSAGING_SERVICE_SID";

        if ($sid === "YOUR_TWILIO_ACCOUNT_SID" || empty($sid)) {
            return false;
        }

        try {
            $twilio = new Client($sid, $token);
            $message = $twilio->messages->create(
                $number, // to
                array(
                    "messagingServiceSid" => $service_sid,
                    "body" => $message_code
                )
            );
            return $message;
        } catch (\Exception $e) {
            error_log("Twilio SMS failed: " . $e->getMessage());
            return false;
        }
    }

}