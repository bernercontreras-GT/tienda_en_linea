<?php
require_once 'auth.php';
require_once 'config.php';
require_once 'ventas_filtros.php';

// Cabeceras HTTP para forzar la descarga de un archivo Excel
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=reporte_ventas.xls");
header("Pragma: no-cache");
header("Expires: 0");

$res = $mysqli->query($sql);

// Encabezados de las columnas
echo "ID Venta\tFecha\tCliente\tTotal\n";

// Contenido del archivo Excel
while($v = $res->fetch_assoc()) {
    echo "{$v['id']}\t{$v['creado_en']}\t{$v['cliente']}\t{$v['total']}\n";
}
?>
