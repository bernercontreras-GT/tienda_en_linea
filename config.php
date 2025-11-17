<?php
// $host = "localhost";
// $user = "desarrollo344";
// $pass = "344desarrollo";
// $db = "bd_344tienda";
// $mysqli = new mysqli("$host","$user","$pass","$db");

$mysqli = new mysqli("localhost","ejemplocrud","crudejemplo","bd_344tienda");

if($mysqli->connect_errno){
   error_log("error de conexion a DB: ".$mysqli->connect_error); ///////////////////////////
    //die("Error de conexión".$mysqli->connect_error);
    exit;
}
// else{
//  die("Conexion correcta");
// }

$mysqli->set_charset("utf8mb4");

function limpiar($s){
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
?>