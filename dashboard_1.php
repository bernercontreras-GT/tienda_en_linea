<?php
require_once 'auth.php';
//require_once 'config.php';
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
        <h1>Panel Princial</h1>
        <p>Bienvenido,  <?=limpiar($_SESSION['rol'])?>. Elije la acción a realizar</p>
        
        <!-- Creacion de botones para manupulacion del sistema -->
      <a class="btn" href="users.php">Usuarios</a>
      <a class="btn" href="products.php">Productos </a>
      <a class="btn" href="ventas.php">Ventas</a>
      <a class="btn" href="catalog.php">Catálogo</a>
      <a class="btn" href="cart.php">Carrito</a>
      <a class="btn" href="logout.php">Cerrar Sesión</a>
      
</div>
</div>



</body>
</html>