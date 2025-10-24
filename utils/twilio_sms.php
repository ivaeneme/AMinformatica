<?php
// Archivo: utils/twilio_sms.php

require_once __DIR__ . 'vendor/twilio-php-main/src/Twilio/autoload.php';

use Twilio\Rest\Client;

class TwilioSMS {
    private $sid = 'TU_ACCOUNT_SID';  // 🔹 reemplazá por el tuyo
    private $token = 'TU_AUTH_TOKEN';  // 🔹 reemplazá por el tuyo
    private $from = '+14155552671';    // 🔹 reemplazá por tu número Twilio

    public function enviarCodigo($numeroDestino, $codigo) {
        try {
            $client = new Client($this->sid, $this->token);
            $mensaje = "Tu código de recuperación AM Informática es: $codigo";

            $client->messages->create(
                $numeroDestino,
                [
                    'from' => $this->from,
                    'body' => $mensaje
                ]
            );

            return true;
        } catch (Exception $e) {
            error_log("Error al enviar SMS: " . $e->getMessage());
            return false;
        }
    }
}
