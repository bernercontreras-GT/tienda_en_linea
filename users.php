<?php


//control de acceso
//solamente los usuarios con rol de administrador tendran acceso 
//require_once 'config.php';
require_once 'auth.php';

if($_SESSION['rol'] !== 'admin'){
die('Acceso restringido'); //si no es un usuario admon, mostramos mensaje y detemos la aplicaicon
}

///variables auxiliares
$usuarioEditar = null; //para acarga los datos en el formulario de edicion 

////////////// Acciones: CREAR , ELIMINAR, EDITAR, ACTUALIZAR, CAMBIAR PASSEORD ------------
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    ///Crear
    if(isset($_POST['crear'])){
        $stmt = $mysqli -> prepare("INSERT INTO usuarios(nombre,email,password,rol) VALUES (?,?,?,?)");
        
        $stmt->bind_param('ssss', $_POST['nombre'], $_POST['email'],$_POST['password'],$_POST['rol']);//en el primer campo del parentesis damos la instruccion del tipo de dato que vamos a recibir
        $stmt ->execute();
    }

    //Eliminar
    if(isset($_POST['eliminar'])){
        $id = (int)$_POST['id'];
        $mysqli->query("DELETE FROM usuarios WHERE id=$id");

    }

    //Seleccionar para editar
    if (isset($_POST['editar'])){
        $id = (int)$_POST['id'];
        $resSel = $mysqli->query("SELECT*FROM usuarios WHERE id=$id");
        $usuarioEditar = $resSel ->fetch_assoc();
    }

    ///funcion para actualizar los datos de usuario 
    if(isset($_POST['actualizar'])){
        $id = (int)$_POST['id'];
        $stmt = $mysqli ->prepare("UPDATE usuarios SET nombre=?, email=?, rol=? WHERE id=?");
        $stmt ->bind_param('sssi', $_POST['nombre'],$_POST['email'],$_POST['rol'],$id);
        $stmt ->execute();
    }

    ///Cambiar contraseña 
    if(isset($_POST['cambiar_password'])){
        $id = (int)$_POST['id'];
        $stmt = $mysqli ->prepare("UPDATE usuarios SET password=? WHERE id=?");
        $stmt ->bind_param('si', $_POST['password'],$id);
        $stmt ->execute();
    }
}
    $res = $mysqli->query("SELECT * FROM usuarios");
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
    <link rel="stylesheet" href="users.css">
</head>
<body>
    <div class="container">
        <div class="card">

                <h1>Gestión de Usuarios</h1>
                <table class="table">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                    <?php while($u= $res -> fetch_assoc()): ?>
                        <tr>
                        <td><?= $u['id'] ?> </td> <!--Columna par llenar el ID-->
                        <td><?= limpiar($u['nombre']) ?></td> <!--Llenar el nombre-->
                        <td><?= limpiar($u['email']) ?></td> <!-- llenar el email-->
                        <td><?= $u['rol'] ?></td> <!--llenar el rol-->
                        <td> <!--Columna de acciones-->
                            <!--Bóton de eliminar-->
                            <form method="post" style="display:inline">
                                <input type="hidden" name="id" value="<?= $u['id'] ?>"  >
                                <button type="submit" name="eliminar" class="btn danger"
                                    onclick="return confirm('¿Seguro que desea eliminar este producto?')">
                                    Eliminar
                                </button>
                            </form>
                            <!--Boton para editar -->
                            <form method="post" style="display:inline">
                                <input type="hidden" name="id" value="<?= $u['id'] ?>"  >
                                <button type="submit" name="editar" class="btn danger">
                                    Editar
                                </button>
                            </form>
                        </td>
                        </tr>
                        <?php endwhile; ?>
                </table>

            <!-- formularios para poder crear, actualizar, editar -->
            <!-- Formulario para crear los usuarios -->
            <h2>Nuevo Usuario</h2>
            <form  method="post">
                <input name="nombre" placeholder="Nombre" required>
                <input name="email" type="email" placeholder="Email" required>
                <input name="password" type="password" placeholder="Password" required>
                <select name="rol" id="">
                    <option value="empleado">Empleado</option>
                    <option value="admin">Admin</option>
                    <option value="cliente">Cliente</option>
                </select>
                <button name="crear" class="btn primary">Crear</button>
            </form>


            <!-- Formulario para actualizar usuarios -->
            <?php if($usuarioEditar): ?>
                <h2>Editar Usuario</h2>
                <form method="post">
                <input type="hidden" name="id" value="<?= $usuarioEditar['id']?>" required>
                <input name="nombre" value="<?=limpiar($usuarioEditar['nombre'])?>" required>
                <input type="email" name="email" value="<?= limpiar($usuarioEditar['email'])?>">
                <select name="rol" id="">
                    <option value="empleado" <?=$usuarioEditar['rol'] =='empleado'?'selected':''?> >Empleado</option>
                    <option value="admin" <?=$usuarioEditar['rol'] =='admin'?'selected':''?> >Admin</option>
                    <option value="cliente" <?=$usuarioEditar['rol'] =='cliente'?'selected':''?> >Cliente</option>
                </select>
                <button type="submit" name="actualizar" class="btn danger"
                            onclick="return confirm('¿Seguro que desea guardar los datos?')">
                            Actualizar
                        </button>

                <!-- <button name="actualizar" class="btn warning">Actualizar</button> -->
            </form>
            <!-- Formulario para cambiar la contraseña-->
                <h2>Cambiar Contraseña</h2>
                <form action="post">
                    <input type="hidden" name="id" value="<?= $usuarioEditar['id']?>" required>
                    <input type="password" name="password" placeholder="Nueva Contraseña" required>
                    <button type="submit" name="cambiar_password" class="btn secondary" 
                    onclick ="return confirm('Contraseña cambiada correctamente')">Cambiar Contraseña</button>
                </form>
            <?php endif;?>

            <a href="dashboard.php" class="btn">Regresar al menú</a>
        </div>
    </div>
    
</body>
</html>