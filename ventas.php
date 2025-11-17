<?php
require_once 'auth.php';
require_once 'config.php'; //-**********************

// Solo administradores pueden acceder
if ($_SESSION['rol'] !== 'admin') {
    die('Acceso restringido');
}

// Importamos los filtros (usa la variable $sql)
include 'ventas_filtros.php';

// Ejecutamos la consulta SQL generada
$res = $mysqli->query($sql);
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Reporte de Ventas</title>
<link rel="stylesheet" href="ventas.css">
</head>
<body>
<div class="container">
  <div class="card">
    <h1>📊 Reporte de Ventas</h1>

    <!-- Formulario de filtros -->
    <form method="get" class="filtros">
      <h3>Filtros de búsqueda</h3>

      <label>Fecha inicio: <input type="date" name="f_inicio" value="<?= limpiar($f_inicio) ?>"></label>
      <label>Fecha fin: <input type="date" name="f_fin" value="<?= limpiar($f_fin) ?>"></label>
      <label>Monto mínimo: <input type="number" step="0.01" name="monto_min" value="<?= limpiar($monto_min) ?>"></label>
      <label>Monto máximo: <input type="number" step="0.01" name="monto_max" value="<?= limpiar($monto_max) ?>"></label>
      <label>Mes: 
        <select name="mes">
          <option value="">-- Todos --</option>
          <?php for ($i=1;$i<=12;$i++): ?>
            <option value="<?= $i ?>" <?= ($mes==$i?'selected':'') ?>><?= date("F", mktime(0,0,0,$i,1)) ?></option>
          <?php endfor; ?>
        </select>
      </label>
      <label class="label">Cliente: <input type="text" name="cliente" value="<?= limpiar($cliente) ?>" placeholder="Nombre del cliente"></label>
      <label class="label">Producto: <input type="text" name="producto" value="<?= limpiar($producto) ?>" placeholder="Nombre del producto"></label>

      <button type="submit" class="btn primary">Filtrar</button>
      <a href="ventas.php" class="btn">Limpiar</a>
    </form>

    <!-- Botones de exportación -->
    <div class="acciones">
      <button onclick="window.print()" class="btn">🖨️ Imprimir</button>
      <a href="export_pdf.php?<?= http_build_query($_GET) ?>" class="btn primary">📄 Exportar PDF</a>
      <a href="export_excel.php?<?= http_build_query($_GET) ?>" class="btn primary">📊 Exportar Excel</a>
    </div>

    <!-- Tabla de resultados -->
    <table class="table">
      <tr>
        <th>ID Venta</th>
        <th>Fecha</th>
        <th>Cliente</th>
        <th>Total</th>
        <th>Acción</th>
      </tr>
      <?php if($res && $res->num_rows > 0): ?>
        <?php while($v = $res->fetch_assoc()): ?>
          <tr>
            <td><?= $v['id'] ?></td>
            <td><?= $v['creado_en'] ?></td>
            <td><?= limpiar($v['cliente']) ?></td>
            <td>Q <?= number_format($v['total'],2) ?></td>
            <td><a href="receipt.php?id=<?= $v['id'] ?>" class="btn">Ver boleta</a></td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="5">No se encontraron ventas</td></tr>
      <?php endif; ?>
    </table>

    <a href="dashboard.php" class="btn">Volver</a>
  </div>
</div>
</body>
</html>
