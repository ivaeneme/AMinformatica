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
        $apiKey = 'xxxx';
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

    
    public static function enviarFactura($factura, $items)
    {
        $email = new Mail();

        // Remitente (usá el verificado en SendGrid)
        $email->setFrom("ivan_max_martinez94@hotmail.com", "AM Informática");

        // Destinatario
        $email->addTo($factura['correo_cliente'], $factura['nombre_cliente']);

        // Asunto
        $email->setSubject("Factura #" . $factura['idFactura'] . " - AM Informática");

        // Empresa (podés sacar esto a config si querés)
        $empresaNombre   = "AM Informática";
        $empresaDomicilio = "Pirovano 109";
        $empresaFiscal   = "CUIT: 20-12345678-9";

        $fechaEmision = date("d/m/Y", strtotime($factura['fechaEmision']));
        $vencimiento  = date('d/m/Y', strtotime($factura['fechaEmision'] . ' +7 days'));

        // Armamos filas de items
        $filas = "";
        foreach ($items as $item) {
            $pu    = $item['cantidad'] > 0 ? $item['costoSubTotal'] / $item['cantidad'] : 0;
            $filas .= "
            <tr>
                <td>{$item['idListaPresupuesto']}</td>
                <td>{$item['descripcion']}</td>
                <td>{$item['cantidad']}</td>
                <td>$" . number_format($pu, 2, ',', '.') . "</td>
                <td>$" . number_format($item['costoSubTotal'], 2, ',', '.') . "</td>
            </tr>
        ";
        }

        // HTML del correo (versión liviana basada en tu ver_factura.php)
        $html = "
    <div style='font-family:Arial,sans-serif;color:#333;'>
        <h2>Factura #{$factura['idFactura']}</h2>
        <p>Hola <strong>{$factura['nombre_cliente']}</strong>,</p>
        <p>Te enviamos el detalle de tu factura emitida por <strong>$empresaNombre</strong>.</p>

        <table width='100%' cellpadding='6' cellspacing='0' style='border-collapse:collapse;margin-top:10px;font-size:13px;'>
            <tr>
                <td style='border:1px solid #ddd;'>
                    <b>Cliente:</b> {$factura['nombre_cliente']}<br>
                    <b>DNI:</b> {$factura['dni_cliente']}<br>
                    <b>Email:</b> {$factura['correo_cliente']}
                </td>
                <td style='border:1px solid #ddd;'>
                    <b>N° Factura:</b> {$factura['idFactura']}<br>
                    <b>Fecha:</b> $fechaEmision<br>
                    <b>Vencimiento:</b> $vencimiento
                </td>
            </tr>
        </table>

        <h3 style='margin-top:15px;'>Detalle</h3>
        <table width='100%' cellpadding='6' cellspacing='0' style='border-collapse:collapse;font-size:13px;'>
            <thead>
                <tr style='background:#e8f0ff;'>
                    <th style='border:1px solid #b3c5ff;'>Código</th>
                    <th style='border:1px solid #b3c5ff;'>Artículo</th>
                    <th style='border:1px solid #b3c5ff;'>Cant.</th>
                    <th style='border:1px solid #b3c5ff;'>P. Unitario</th>
                    <th style='border:1px solid #b3c5ff;'>Total</th>
                </tr>
            </thead>
            <tbody>
                $filas
            </tbody>
        </table>

        <p style='text-align:right;margin-top:10px;font-size:15px;'>
            <b>Total factura: $" . number_format($factura['total'], 2, ',', '.') . "</b>
        </p>

        <hr>
        <p style='font-size:11px;color:#777;'>
            $empresaNombre - $empresaDomicilio - $empresaFiscal
        </p>
    </div>
    ";

        $email->addContent("text/html", $html);

        // API KEY (dejá tu valor real acá o leela desde un archivo seguro)
        $apiKey = 'xxxx';
        $sendgrid = new \SendGrid($apiKey);

        try {
            $response = $sendgrid->send($email);
            return $response->statusCode() == 202;
        } catch (Exception $e) {
            error_log("Error SendGrid (factura): " . $e->getMessage());
            return false;
        }
    }
}
