<?php
//llama ak archivo para proteger la pagina de inicio es decir que el usuario debe de estar logeado 
require_once 'auth.php';
//require_once 'config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


//obener el carito de compras si no existe asignara un arreglo vacio
$cart = $_SESSION['cart'] ?? [];

$items = []; //un arreglo vacio para almacenar los productos

$total = 0; //variable para el total de la compra 

if ($cart) {
    //obtener los id de los productos del carrito, los convertimos a enteros
    //implode en php sirve para unir los elementos de un array en una sola cadena
    $ids = implode(',', array_map('intval', array_keys($cart)));
    $res = $mysqli->query("SELECT * FROM productos WHERE id IN ($ids)"); //hacemos una consulta a la base de datos para obtener todos los productos, los id esten dentro del carrito 
    // recorrido de todos los productos obtenidos de la base de datos
      while ($p = $res->fetch_assoc()) {
        //cantidad de los productos en el carrito
        $cant = $cart[$p['id']];
        //  subtotal de calculado por el precio del preoducto 
        $sub = $cant * $p['precio'];
        // suma total del carrito 
        $total += $sub;
        // agregamo al arreglo los productos, la cantidad y el subtotal 
        $items[] = ['producto' => $p, 'cantidad' => $cant, 'subtotal' => $sub];
    }
}

?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🛒 Carrito de compras</title>
    <link rel="stylesheet" href="cart.css">
</head>
<body>
<div class="container">
  <div class="card">
    <h1>🛒 Carrito de compras</h1>
    <!-- si no hay productos en el carrito, mostramos  -->
    <?php if (!$items): ?>
      <p>Tu carrito está vacio, ¡Añade algunos productos!</p>
      <a href="catalog.php" class= "btn secondary">Volver al Catálogo</a>
   
    <?php else: ?>
      <!-- si hay productos , mostramos una tabla -->
       <table class="table" border="1" cellpadding="10">
        <thead>
          <tr>
            <th>Imagen</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio Unitario</th>
            <th>Subtotal</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <!-- Recorremos el arreglo de items para mostrar cada producto -->
         <tbody>
              <?php foreach($items as $it): ?>
                <tr>
                  <td>
                    <?php  if (!empty($it['producto']['imagen'])): ?>
                       <img src="<?= limpiar($it['producto']['imagen']) ?>" width="50" alt="<?= limpiar($it['producto']['nombre']) ?>">

                    <?php endif; ?>
                  </td>
                  <!-- Nombre del producto (funcion lipiar para evita que existan inyecciones de caracteres extraños) -->
                  <td><?= limpiar($it['producto']['nombre']) ?></td>
                  <!-- cantiad del producto -->
                  <td><?= $it['cantidad'] ?></td>
                  <!-- precio unitario -->
                  <td>Q <?= $it['producto']['precio'] ?></td>
                  <!-- subtotal = cantidad * precio -->
                  <td>Q <?= $it['subtotal'] ?></td>
                  <td>
                    <button class="btn danger" onclick="deleteFromCart(<?= $it['producto']['id']?>)">Eliminar
                    </button>
                  </td>

                </tr>
              <?php endforeach; ?>
        </tbody>
      </table>
      <h2>Total a pagar: Q  <?=number_format($total,2)?></h2>

      <div style="margin-top: 20px;">
        <form method="post" action="checkout.php" style= "display: inline;">
          <button class="btn primary">Finalizar Compra</button>
        </form>
        <a href="catalog.php" class= "btn secondary">Seguir Comprando</a>
        <button class="btn danger" onclick="clearCart()">
          Cancelar Compra
        </button>
      </div>
    <?php endif; ?>
    
  </div>
</div>
<script src= "cart.js"></script>

</body>
</html>
