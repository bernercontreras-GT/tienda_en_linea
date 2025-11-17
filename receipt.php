<?php
// Incluye auth.php para proteger la página (solo usuarios logueados pueden verla)
require_once 'auth.php';
//require_once 'config.php';
// Obtenemos el ID de la venta desde la URL mediante GET
// Si no existe, asignamos 0 por defecto y convertimos a entero por seguridad
$id = (int)($_GET['id'] ?? 0);

// Obtenemos la información general de la venta (tabla 'ventas') según el ID
$venta = $mysqli->query("SELECT * FROM ventas WHERE id=$id")->fetch_assoc();

// Obtenemos los detalles de la venta (productos comprados) y sus nombres
// Se hace un JOIN con la tabla 'productos' para obtener el nombre de cada producto
$det = $mysqli->query("
    SELECT vd.*, p.nombre 
    FROM venta_detalle vd 
    JOIN productos p ON p.id = vd.producto_id 
    WHERE vd.venta_id = $id
");
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Comprobante de compra</title>
  <link rel="stylesheet" href="receipt.css">
</head>
<body>
  <div class="container"> <!-- Contenedor principal -->
      <div class="receipt-card"> <!-- Tarjeta visual -->

        <div class="header" >
            <h1>Recibo de compra</h1>
            <div class="img-logo">
            <img src="./img/Logo.jpg" alt="">
            </div>
            <!-- Mostramos el número de la boleta -->
            <h3>Venta No.<?= limpiar($venta['id']) ?></h3>
        </div>
        <div class="info-compra">  
          <!-- Mostramos fecha de creación y total de la venta -->
          <strong><p>Fecha de compra: <br></strong> <?= limpiar($venta['creado_en']) ?></p>

          <!-- Tabla que lista los productos comprados -->
          <h2>Detalle de productos</h2>
          <table class="table">
            <thead>
              <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio unitario</th>
                <th>Subtotal</th>
              </tr>
            </thead>

            <tbody>        <!-- Recorremos cada producto comprado -->
              <?php while($r = $det->fetch_assoc()): ?>
              <tr>
                <!-- Nombre del producto sanitizado -->
                <td><?= limpiar($r['nombre']) ?></td>
                <!-- Cantidad comprada -->
                <td><?= $r['cantidad'] ?></td>
                <!-- Precio unitario -->
                <td id="precio"><?= $r['precio_unitario'] ?></td>
                <!-- Subtotal -->
                <td id="precio"><?= $r['subtotal'] ?></td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>

          <div class="total-line">
            <br>
            <strong><h3>
            Total de compra: 
            <br>
            Q <?=limpiar($venta['total'])?></h3>
            </strong>
          </div>
                <div class="actions">
                  <button onclick="imprimirRecibo()" class = "btn print-btn">
                    🖨️ Imprimir Recibo
                  </button>
                </div>
              <a href="catalog.php" class="btn back-btn">Volver al catálogo</a>
                <!-- Botón para regresar al dashboard --> 
              <a href="dashboard.php" class="btn back-btn">Menú principal</a>
        </div>
      </div>
  </div>
  <script src="cart.js"></script>
</body>
</html>