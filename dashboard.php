<?php
require_once 'auth.php';
require_once 'config.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
<div class="container">
    <div class="card">
        <!-- Encabezado con usuario y rol -->
        <h1>Panel Principal</h1>
        <p>Bienvenido, <strong><?= limpiar($_SESSION['nombre']) ?></strong> Rol: <strong><?= limpiar($_SESSION['rol']) ?></strong></p>
        <p>Elige la acción a realizar:</p>

        <!-- Botones solo para admin -->
        <?php if($_SESSION['rol'] === 'admin'): ?>
            <a class="btn" href="users.php">Usuarios</a>
            <a class="btn" href="products.php">Productos</a>
            <a class="btn" href="ventas.php">Ventas</a>
        <?php endif; ?>

        <!-- Botones accesibles para todos los roles -->
        <a class="btn" href="catalog.php">Catálogo</a>
        <a class="btn" href="cart.php">Carrito</a>
        <a class="btn" href="logout.php">Cerrar Sesión</a>
    </div>
</div>
</body>
</html>
