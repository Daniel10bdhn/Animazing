<?php
require_once __DIR__ . "/../Config/verificar_sesion.php";
require_once __DIR__ . "/../Config/conexion.php";


$id_usuario = $_SESSION['id_usuario'];

// Consulta con JOIN para obtener el nombre de la mascota
$sql = "SELECT an.nombre AS nombre_mascota, a.fecha_adopcion, a.estado
        FROM adopciones a
        INNER JOIN animales an ON a.animal_id = an.id
        WHERE a.usuario_id = ?";
        
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    die("Error al preparar la consulta: " . $conexion->error);
}

$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Adopciones</title>
</head>
<body>
<h2>📋 Mis Adopciones</h2>
<table border="1">
    <tr>
        <th>Mascota</th>
        <th>Fecha</th>
        <th>Estado</th>
    </tr>
    <?php while ($fila = $resultado->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($fila['nombre_mascota']) ?></td>
            <td><?= htmlspecialchars($fila['fecha_adopcion']) ?></td>
            <td><?= htmlspecialchars($fila['estado']) ?></td>
        </tr>
    <?php endwhile; ?>
</table>
</body>
</html>

