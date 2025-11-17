<?php
require_once 'auth.php';
//require_once 'config.php';
// Inicia o reanuda la sesión
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Obtenemos el carrito desde la sesión; si no existe, será un arreglo vacío
$cart = $_SESSION['cart'] ?? [];

// Si el carrito está vacío, redirigimos al archivo cart.php (porque no tiene sentido procesar compra sin productos)
if (!$cart) {
    header('Location: cart.php');
    exit; // Detenemos la ejecución del script
}

// Obtenemos los IDs de los productos en el carrito
// - array_keys($cart) devuelve las claves (IDs de producto)
// - array_map('intval', ...) asegura que todos sean enteros (seguridad contra inyección SQL)
// - implode(',', ...) los convierte en una cadena separada por comas (ej: "1,2,3")
$ids = implode(',', array_map('intval', array_keys($cart)));

// Consultamos en la base de datos los productos que coinciden con los IDs
$res = $mysqli->query("SELECT * FROM productos WHERE id IN ($ids)");

// Inicializamos un arreglo para los productos y un total general
$productos = [];
$total = 0;

// Recorremos cada producto obtenido de la base
while ($p = $res->fetch_assoc()) {
    // Obtenemos la cantidad de este producto desde el carrito
    $cant = $cart[$p['id']];

    // Calculamos el subtotal (cantidad * precio unitario)
    $sub = $cant * $p['precio'];

    // Sumamos el subtotal al total general
    $total += $sub;

    // Guardamos los datos del producto en el arreglo $productos
    $productos[] = [
        'id' => $p['id'],
        'precio' => $p['precio'],
        'cantidad' => $cant,
        'subtotal' => $sub
    ];
}

try {
    // Iniciamos una transacción en la base de datos
    // Esto asegura que todas las operaciones se hagan juntas o ninguna (integridad de datos)
    $mysqli->begin_transaction();

    // Insertamos la venta en la tabla "ventas"
    $stmt = $mysqli->prepare("INSERT INTO ventas(usuario_id, total) VALUES (?, ?)");
    // Enlazamos parámetros: usuario_id (int) y total (decimal)
    $stmt->bind_param('id', $_SESSION['usuario_id'], $total);
    // Ejecutamos la inserción
    $stmt->execute();
    // Obtenemos el ID autogenerado de la venta recién insertada
    $vid = $stmt->insert_id;

    // Preparamos la sentencia para insertar detalles de la venta
    $det = $mysqli->prepare("INSERT INTO venta_detalle(venta_id, producto_id, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)");
    
    // Recorremos cada producto comprado
    foreach ($productos as $pr) {
        // Enlazamos parámetros: id venta, id producto, cantidad, precio y subtotal
        $det->bind_param('iiidd', $vid, $pr['id'], $pr['cantidad'], $pr['precio'], $pr['subtotal']);
        // Ejecutamos la inserción de cada detalle
        $det->execute();
    }

    // Si todo salió bien, confirmamos la transacción en la base
    $mysqli->commit();

    // Limpiamos el carrito (dejamos el array vacío)
    $_SESSION['cart'] = [];

    // Redirigimos al usuario a la página de recibo/boleta con el id de la venta
    header("Location: receipt.php?id=$vid");
    exit;
} catch (Exception $e) {
    // Si ocurre algún error, revertimos todos los cambios en la base de datos
    $mysqli->rollback();
    // Mostramos un mensaje de error (en un proyecto real, sería mejor registrarlo en logs)
    echo "Error al procesar la venta: " . $e->getMessage();
    exit;
}

?>