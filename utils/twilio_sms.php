<?php
// Archivo: utils/twilio_sms.php

require_once __DIR__ . '/../vendor/twilio-php-main/src/Twilio/autoload.php';


use Twilio\Rest\Client;

class TwilioSMS {
    private $sid = 'AC6ae2ed098005d3c5f4ef9493acd00440';  // 🔹 reemplazá por el tuyo
    private $token = 'a56eb3cf56143639ab67e575b0bf2d77';  // 🔹 reemplazá por el tuyo
    private $from = '++16207368121';    // 🔹 reemplazá por tu número Twilio

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
