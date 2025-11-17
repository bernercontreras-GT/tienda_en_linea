<?php
// Archivo: ventas_filtros.php
// ---------------------------------------------
// Este archivo genera la variable $sql que contiene
// la consulta SQL filtrada para listar las ventas.
// Se incluye desde ventas.php, export_pdf.php y export_excel.php
// ---------------------------------------------

// Variables de filtros (recibidas por GET)
$f_inicio = $_GET['f_inicio'] ?? '';
$f_fin = $_GET['f_fin'] ?? '';
$monto_min = $_GET['monto_min'] ?? '';
$monto_max = $_GET['monto_max'] ?? '';
$mes = $_GET['mes'] ?? '';
$cliente = $_GET['cliente'] ?? '';
$producto = $_GET['producto'] ?? '';

// Arreglo donde se almacenarán las condiciones del WHERE
$where = [];

// Filtro por rango de fechas
if ($f_inicio && $f_fin) {
    $where[] = "v.creado_en BETWEEN '$f_inicio' AND '$f_fin'";
}

// Filtros por montos
if ($monto_min !== '') {
    $where[] = "v.total >= " . floatval($monto_min);
}
if ($monto_max !== '') {
    $where[] = "v.total <= " . floatval($monto_max);
}

// Filtro por mes específico
if ($mes) {
    $where[] = "MONTH(v.creado_en) = " . intval($mes);
}

// Filtro por cliente
if ($cliente) {
    $where[] = "u.nombre LIKE '%" . $mysqli->real_escape_string($cliente) . "%'";
}

// Filtro por producto
if ($producto) {
    $where[] = "p.nombre LIKE '%" . $mysqli->real_escape_string($producto) . "%'";
}

// Consulta SQL con JOIN a tablas relacionadas
$sql = "
    SELECT v.id, v.creado_en, v.total, u.nombre AS cliente
    FROM ventas v
    JOIN usuarios u ON u.id = v.usuario_id
    LEFT JOIN venta_detalle vd ON vd.venta_id = v.id
    LEFT JOIN productos p ON p.id = vd.producto_id
";

// Si hay filtros, se agregan al WHERE
if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

// Agrupamos por ID de venta (por si se repite producto)
$sql .= " GROUP BY v.id ORDER BY v.creado_en DESC";
?>
