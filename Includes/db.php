<?php
$host = "localhost";           // En XAMPP suele ser localhost
$usuario = "root";             // Usuario por defecto suele ser 'root'
$password = "";                // Contraseña por defecto suele estar vacía
$baseDeDatos = "animazing";    // Cambia aquí por el nombre de tu base de datos

// Crear conexión
$conn = mysqli_connect($host, $usuario, $password, $baseDeDatos);

// Verificar conexión
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}
// echo "Conexión exitosa"; // Puedes usar esto para probar si se conecta bien
?>
