<?php
// ===============================================================
// ARCHIVO: cart_actions.php
// Propósito: Controlador del carrito de compras.
// Recibe peticiones JavaScript (fetch) con acciones (add, remove, clear),
// actualiza el carrito en la sesión y devuelve JSON limpio.
// ===============================================================

// Incluir la autenticación para asegurar que el usuario esté logueado
require_once 'auth.php';

// Iniciar o continuar la sesión (por si no está iniciada)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Limpia cualquier salida previa (por ejemplo, espacios o errores)
ob_clean();

// Configurar encabezado para devolver JSON
header('Content-Type: application/json; charset=utf-8');

// Inicializar el carrito si no existe
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// ---------------------------------------------------------------
// Capturar variables desde la solicitud POST
// ---------------------------------------------------------------
$action = $_POST['action'] ?? '';             // Acción (add, remove, clear)
$pid = (int)($_POST['product_id'] ?? 0);      // ID del producto
$qty = (int)($_POST['qty'] ?? 1);             // Cantidad

// ---------------------------------------------------------------
// Manejo de acciones del carrito
// ---------------------------------------------------------------

// Agregar producto al carrito
if ($action === 'add' && $pid) {
    // Si el producto ya está en el carrito, se suma la cantidad
    $_SESSION['cart'][$pid] = ($_SESSION['cart'][$pid] ?? 0) + $qty;
    $response = [
        'status' => 'success',
        'message' => 'Producto agregado al carrito.',
        'cart' => $_SESSION['cart']
    ];
    echo json_encode($response);
    exit;
}

// Eliminar producto del carrito
if ($action === 'remove' && $pid) {
    unset($_SESSION['cart'][$pid]);
    $response = [
        'status' => 'success',
        'message' => 'Producto eliminado del carrito.',
        'cart' => $_SESSION['cart']
    ];
    echo json_encode($response);
    exit;
}

// Vaciar el carrito
if ($action === 'clear') {
    $_SESSION['cart'] = [];
    $response = [
        'status' => 'success',
        'message' => 'Carrito vaciado correctamente.',
        'cart' => []
    ];
    echo json_encode($response);
    exit;
}

// ---------------------------------------------------------------
// Si llega aquí, la acción no es válida
// ---------------------------------------------------------------
$response = [
    'status' => 'error',
    'message' => 'Acción no reconocida.',
    'cart' => $_SESSION['cart']
];
echo json_encode($response);
exit;
?>