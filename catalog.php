<?php
require_once 'auth.php';
//require_once 'config.php';
$res = $mysqli->query("SELECT id, nombre, descripcion, precio, stock, imagen FROM productos");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🛒 Catálogo de productos</title>
    <link rel="stylesheet" href="catalog.css">
</head>
<body>
    <div class="container">
    <h1>Catálogo de Productos</h1>
        
        <a href="cart.php" class="btn secondary">Ver Carrito 🛒</a>
        <a href="dashboard.php" class="btn secondary">Menú 📚</a>
        <div class="product-grid">
            <?php while ($p = $res->fetch_assoc()): ?>
                <div class="product-card">
                    
                    <?php if ($p['imagen']): ?>
                        <img 
                            src="<?= limpiar($p['imagen']) ?>" 
                            alt="<?= limpiar($p['nombre']) ?>"
                        >
                    <?php endif; ?>

                    <h2><?= limpiar($p['nombre']) ?></h2> 
                    <p><?= limpiar($p['descripcion']) ?></p> 
                    <p><strong>Precio: Q <?= limpiar($p['precio']) ?></strong></p>
                    <p><strong>Existencia: <?= limpiar($p['stock']) ?></strong></p>
                    <button 
                        class="btn primary" 
                        onclick="addtoCart(<?= $p['id'] ?>)">
                        Agregar al Carrito
                    </button>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="cart.js"></script>
</body>
</html>