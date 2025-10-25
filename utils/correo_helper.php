<?php
require_once __DIR__ . '/sendgrid-php/vendor/autoload.php'; // Autoload real

use SendGrid\Mail\Mail;

class CorreoHelper
{
    public static function enviarTokenRecuperacion($destinatario, $nombre, $token)
    {
        $email = new Mail();
        $email->setFrom("ivan_max_martinez94@hotmail.com", "AM Informática");
        $email->setSubject("Recuperación de contraseña");
        $email->addTo($destinatario, $nombre);
        $email->addContent(
            "text/html",
            "
            <h2>Recuperación de contraseña</h2>
            <p>Hola <strong>$nombre</strong>,</p>
            <p>Tu código de verificación es:</p>
            <h3 style='color:#2c3e50;'>$token</h3>
            <p>Este código expirará en 10 minutos.</p>
            <p>Saludos,<br>AM Informática</p>
            "
        );

        // ⚙️ Clave de API (desde tu panel de SendGrid)
        // $apiKey = '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';
        $sendgrid = new \SendGrid($apiKey);

        try {
            $response = $sendgrid->send($email);

            // Para depurar (opcional)
            // echo $response->statusCode();
            // print_r($response->headers());
            // echo $response->body();

            return $response->statusCode() == 202;
        } catch (Exception $e) {
            echo '<pre>';
            echo "❌ Error al enviar correo: " . $e->getMessage() . "\n\n";
            if (isset($response)) {
                echo "Código de estado: " . $response->statusCode() . "\n";
                echo "Respuesta del servidor:\n";
                print_r($response->body());
            }
            echo '</pre>';
            return false;
        }
    }
}
