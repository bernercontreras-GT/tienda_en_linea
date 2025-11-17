<?php
require_once 'auth.php';
//require_once 'config.php';

    //procedimientos para eliminar o actualizar el producto
if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        //boton crear
        if(isset($_POST['crear'])){
            $nombre = $_POST['nombre'];
            $desc = $_POST['descripcion'];
            $precio = $_POST['precio'];
            $stock = $_POST['stock'];
            $img = '';

            if (!empty($_FILES['imagen']['name'])){
                $img = 'uploads/' . basename($_FILES['imagen']['name']);
                move_uploaded_file($_FILES['imagen']['tmp_name'], $img);
            }
            $stmt = $mysqli->prepare("INSERT INTO productos(nombre, descripcion, precio, stock, imagen) VALUES (?,?,?,?,?)" );
            $stmt->bind_param('ssdis',$nombre, $desc, $precio, $stock, $img );
            $stmt ->execute();
        }

        if(isset($_POST['eliminar'])){
           $id =(int)$_POST['id'];
           $mysqli->query("DELETE FROM productos WHERE id=$id");
        }

        if(isset($_POST['actualizar_guardar'])){
            $id = (int)$_POST['id'];
            $nombre = $_POST['nombre'];
            $desc = $_POST['descripcion'];
            $precio = $_POST['precio'];
            $stock = $_POST['stock'];
           
            // si sube una nueva imagen 
            $img = $_POST['imagen_actual'];
            if(!empty($_FILES['imagen']['name'])){
                $img = 'uploads/'.basename($_FILES['imagen']['name']);
                move_uploaded_file($_FILES['imagen']['tmp_name'], $img);

            }

            $stmt = $mysqli->prepare("UPDATE productos SET nombre=?, descripcion=?, precio =?, stock=?, imagen=? WHERE id=?" );
            $stmt->bind_param('ssdisi',$nombre, $desc, $precio, $stock, $img, $id);
            $stmt->execute();
        }
    }
       
        $productoEditar = null;
        if(isset($_POST['editar']))
        {
            $id = (int)$_POST['id'];

            $productoEditar = $mysqli->query("SELECT * FROM productos WHERE id=$id")-> fetch_assoc();
        }

        $res = $mysqli->query("SELECT * FROM productos");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="stylesheet" href="products.css">
</head>
<body>
    <div class="container">
    <div class="card">
        <h1>Gestión de productos</h1>

    
<!-- Tabla para mostrar los productos -->
    <table border="1" cellpadding="5" cellspacing="0">    
        <tr>
            <th>ID</th> <!-- th negrita y cont d es contenido normal-->
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Imagen</th>
            <th>Acciones</th>
        </tr>

        <?php while($p = $res-> fetch_assoc()):?>
            <tr>
                <td><?= $p['id']?></td>
                <td><?= htmlspecialchars($p['nombre'])?></td>
                <td><?= htmlspecialchars($p['descripcion'])?></td>
                <td><?= $p['precio']?></td>
                <td><?= $p['stock']?></td>
                <td>
                    <?php if($p['imagen']): ?>
                        <img src="<?= $p['imagen']?>" width="50">
                    <?php endif ?>
                </td>
                <td>
                    <!-- Formulario para eliminar -->
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                        <button type="submit" name="eliminar" class="btn danger"
                            onclick="return confirm('¿Seguro que desea eliminar este producto?')">
                            Eliminar
                        </button>
                    </form>

                    <!-- Formulario para editar -->
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                        <button type="submit" name="editar" class="btn warning">Actualizar</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

<!-- Formulario de Crear / Editar -->
    <?php if ($productoEditar): ?>
        <h2>Editar producto</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $productoEditar['id'] ?>">
            <label>Nombre: </label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($productoEditar['nombre']) ?>" required><br>

            <label>Descripción: </label>
            <textarea name="descripcion"><?= htmlspecialchars($productoEditar['descripcion']) ?></textarea><br>

            <label>Precio: </label>
            <input type="number" step="0.01" name="precio" value="<?= $productoEditar['precio'] ?>" required><br>

            <label>Stock: </label>
            <input type="number" name="stock" value="<?= $productoEditar['stock'] ?>" required><br>

            <label>Imagen: </label>
            <?php if ($productoEditar['imagen']): ?>
                <img src="<?= $productoEditar['imagen'] ?>" width="50"><br>
            <?php endif; ?>
            <input type="file" name="imagen"><br>
            <input type="hidden" name="imagen_actual" value="<?= $productoEditar['imagen'] ?>">

            <button type="submit" name="actualizar_guardar" class="btn primary">Guardar cambios</button>
        </form>
    <?php else: ?>
        <h2>Nuevo producto</h2>
        <form method="post" enctype="multipart/form-data">
            <label for="Nombre">Nombre: </label>
            <input type="text" name="nombre" placeholder="Nombre del producto" required> <br>

            <label for="">Descripción: </label>
            <textarea name="descripcion" placeholder="Descripción del producto"></textarea><br>

            <label for="">Precio: </label>
            <input type="number" name="precio" step="0.01" placeholder="Precio" required><br>

            <label for="">Stock: </label>
            <input type="number" name="stock" placeholder="Stock" required><br>

            <label for="">Imagen: </label>
            <input type="file" name="imagen"><br>

            <button type="submit" name="crear" class="btn primary">Crear</button>
        </form>
    <?php endif; ?>

    <!-- Botón para regresar al panel principal -->
    <br>
    <a href="dashboard.php" class="btn">Regresar</a>

  </div>
</div>

</body>
</html>