<?php
// Datos de conexión a tu base de datos MySQL
$host = "localhost";          // Host donde corre MySQL (localhost en XAMPP)
$usuario = "root";            // Usuario MySQL (por defecto root en XAMPP)
$password = "";               // Contraseña (vacía por defecto en XAMPP)
$baseDeDatos = "animazing";  // Nombre de tu base de datos

// Crear conexión
$conn = mysqli_connect($host, $usuario, $password, $baseDeDatos);

// Verificar conexión
if (!$conn) {
    die("Error de conexión a la base de datos: " . mysqli_connect_error());
}
// Puedes comentar la línea siguiente después de probar la conexión exitosa
// echo "Conexión a la base de datos exitosa";
?>
