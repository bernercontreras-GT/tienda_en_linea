<?php
// ===============================================================
// ARCHIVO: auth.php
// Propósito: Verificar si el usuario tiene una sesión activa.
// Este archivo se incluye en otros scripts (como cart_actions.php)
// para evitar accesos no autorizados.
// ===============================================================

// Iniciar o reanudar la sesión
session_start();

// Incluir configuración general del sistema (si la usas)
require_once 'config.php';

// ===============================================================
// Validar sesión del usuario
// Si no existe la variable de sesión 'usuario_id', significa que
// el usuario no ha iniciado sesión.
// ===============================================================
if (!isset($_SESSION['usuario_id'])) {

    // -----------------------------------------------------------
    // Detectar si la petición viene de JavaScript (fetch/AJAX)
    // -----------------------------------------------------------
    $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
              strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

    if ($isAjax) {
        // Si es una solicitud AJAX, devolvemos un error JSON
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error' => 'No autenticado. Inicie sesión para continuar.']);
        exit;
    } else {
        // Si es una visita normal del navegador, redirigimos al login o página principal
        header('Location: index.php');
        exit;
    }
}
?>