<?php
require_once __DIR__ . "/../Config/verificar_sesion.php";
require_once __DIR__ . "/../Config/conexion.php";

$id_usuario = $_SESSION['id_usuario'];

// Consulta correcta: unir favoritos con mascotas
$sql = "SELECT m.id, m.nombre, m.foto, m.descripcion
        FROM favoritos f
        INNER JOIN mascotas m ON f.animal_id = m.id
        WHERE f.usuario_id = ?";

$stmt = $conexion->prepare($sql);
if(!$stmt){
    die("Error en la consulta SQL: " . $conexion->error);
}

$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Favoritos</title>
</head>
<body>
<h2>❤️ Mis Favoritos</h2>
<div style="display:flex; flex-wrap:wrap; gap:20px;">
    <?php while ($fila = $resultado->fetch_assoc()): ?>
        <div style="border:1px solid #ccc; padding:10px; width:200px;">
            <img src="../assets/Css/img/<?= htmlspecialchars($fila['foto']) ?>" 
                 width="100%" 
                 alt="<?= htmlspecialchars($fila['nombre']) ?>">
            <h4><?= htmlspecialchars($fila['nombre']) ?></h4>
            <p><?= htmlspecialchars($fila['descripcion']) ?></p>
        </div>
    <?php endwhile; ?>
</div>
</body>
</html>

