<?php
$host = "localhost";     // Servidor de base de datos
$usuario = "root";       // Usuario MySQL (por defecto root en XAMPP)
$clave = "";              // Contraseña MySQL (vacía en XAMPP por defecto)
$bd = "animazing";        // Nombre de la base de datos (importa tu Animazing.sql con este nombre)

$conexion = new mysqli($host, $usuario, $clave, $bd);

// Verificar conexión
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Aseguramos UTF-8
$conexion->set_charset("utf8");
?>
