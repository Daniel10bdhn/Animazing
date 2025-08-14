<?php
session_start();
require_once __DIR__ . "/../Config/conexion.php";

if (!isset($_SESSION['id_usuario'])) {
    echo "Debes iniciar sesión para adoptar una mascota";
    exit;
}

if (!isset($_POST['id_mascota']) || empty($_POST['id_mascota'])) {
    echo "No se recibió el ID de la mascota";
    exit;
}

$usuario_id = $_SESSION['id_usuario'];
$id_mascota = intval($_POST['id_mascota']);

try {
    $sql = "INSERT INTO adopciones (fecha_adopcion, usuario_id, animal_id, estado) 
            VALUES (NOW(), ?, ?, 'pendiente')";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ii", $usuario_id, $id_mascota);

    if ($stmt->execute()) {
        echo "Solicitud de adopción enviada con éxito";
    } else {
        echo "Error al registrar la adopción";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
