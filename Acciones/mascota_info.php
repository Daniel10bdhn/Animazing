<?php
require_once __DIR__ . "/../Config/conexion.php";
header('Content-Type: application/json; charset=utf-8');
error_reporting(0); // Para que warnings no rompan el JSON

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Método no permitido"]);
    exit;
}

$id_mascota = $_POST['id_mascota'] ?? '';

if (empty($id_mascota)) {
    echo json_encode(["error" => "ID de mascota no proporcionado"]);
    exit;
}

$sql = "SELECT id, nombre, descripcion, edad, raza, imagen FROM mascotas WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_mascota);

if ($stmt->execute()) {
    $resultado = $stmt->get_result();
    if ($resultado->num_rows > 0) {
        $mascota = $resultado->fetch_assoc();
        echo json_encode($mascota, JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(["error" => "Mascota no encontrada"]);
    }
} else {
    echo json_encode(["error" => "Error en la consulta"]);
}

$stmt->close();
$conexion->close();

