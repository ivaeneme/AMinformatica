
<?php
// ====== CONFIGURACIÓN (puedes llevar a config.php) ======
$empresa = [
    "nombre" => "AM Informática",
    "domicilio" => "Pirovano 109",
    "fiscal" => "CUIT: 20-12345678-9"
];

// Calcular vencimiento (+30 días)
$vencimiento = date('d/m/Y', strtotime($factura['fechaEmision'].' +7 days'));

$subtotal = $factura['total'] ;

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Factura <?= $factura['idFactura'] ?></title>

<style>
body {
    font-family: Arial, sans-serif;
    zoom: 0.9;
}
.header { text-align: right; font-size: 14px; }
.title {
    text-align: center;
    font-size: 28px;
    font-weight: bold;
    color: #1565c0;
}
.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}
.table th {
    background: #e8f0ff;
    border: 1px solid #b3c5ff;
    padding: 5px;
}
.table td {
    border: 1px solid #b3c5ff;
    padding: 5px;
    height: 28px;
}
.info-box {
    border: 1px solid #b3c5ff;
    padding: 10px;
    font-size: 14px;
}
.text-right { text-align: right; }
.total-box {
    width: 250px;
    float: right;
    margin-top: 10px;
    font-size: 14px;
}
.total-box td {
    padding: 4px;
}
</style>
</head>

<body>

<!-- Empresa -->
<div class="header">
    <strong><?= $empresa['nombre'] ?></strong><br>
    <?= $empresa['domicilio'] ?><br>
    <?= $empresa['fiscal'] ?><br>
</div>

<!-- Título -->
<div class="title">FACTURA</div>

<!-- Información del cliente y factura -->
<table width="100%" style="margin-top:20px;">
<tr>
<td class="info-box" width="50%">
<b>CLIENTE:</b> <?= htmlspecialchars($factura['nombre_cliente']) ?><br>
<b>DNI:</b><?= htmlspecialchars($factura['dni_cliente']) ?> <br>
<b>E-MAIL: </b><?= htmlspecialchars($factura['correo_cliente']) ?><br>

</td>

<td class="info-box" width="50%">
<b>N° FACTURA:</b> <?= $factura['idFactura'] ?><br>
<b>FECHA:</b> <?= date("d/m/Y", strtotime($factura['fechaEmision'])) ?><br>
<b>VENCIMIENTO:</b> <?= $vencimiento ?><br>
</td>
</tr>
</table>

<!-- Tabla Items -->
<table class="table">
<thead>
<tr>
<th>CÓDIGO</th>
<th>ARTÍCULO</th>
<th>CANTIDAD</th>
<th>PRECIO UNITARIO</th>
<th>TOTAL</th>
</tr>
</thead>
<tbody>

<?php foreach ($items as $item): ?>
<tr>
<td><?= $item['idListaPresupuesto'] ?></td>
<td><?= $item['descripcion'] ?></td>
<td><?= $item['cantidad'] ?></td>
<td>$<?= number_format($item['costoSubTotal'] / $item['cantidad'], 2, ',', '.') ?></td>
<td>$<?= number_format($item['costoSubTotal'], 2, ',', '.') ?></td>
</tr>
<?php endforeach; ?>

<?php for($i = count($items); $i < 8; $i++): ?>
<tr><td></td><td></td><td></td><td></td><td></td></tr>
<?php endfor; ?>

</tbody>
</table>

<!-- Totales -->
<table class="total-box">


<tr style="font-weight:bold; font-size:16px;">
<td>TOTAL FACTURA</td>
<td class="text-right">$<?= number_format($factura['total'],2,',','.') ?></td>
</tr>
</table>

<br><br>
<a href="javascript:window.print()" style="padding:8px 15px; background:#3498db; color:white; text-decoration:none;">Imprimir / Descargar PDF</a>
<a href="index.php?controlador=carrito&accion=gestionar" style="padding:8px 15px; background:#777; color:white; text-decoration:none;">Volver</a>
<!-- <a href="index.php?controlador=factura&accion=enviarEmailFactura&id=<?= $factura['idFactura'] ?>"
   style="padding:8px 15px; background:#27ae60; color:white; text-decoration:none; margin-left:10px;">
    Enviar factura por correo
</a> -->


</body>
</html>
