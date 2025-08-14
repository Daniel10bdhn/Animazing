<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../Public/login.php");
    exit;
}

require_once __DIR__ . "/../Config/verificar_sesion.php";
require_once __DIR__ . "/../Config/conexion.php";

$id_usuario = $_SESSION['id_usuario'];

// Consulta adopciones con JOIN para obtener el nombre de la mascota
$sql = "SELECT an.nombre AS nombre_mascota, a.fecha_adopcion, a.estado, a.creado_en, a.actualizado_en
        FROM adopciones a
        INNER JOIN animales an ON a.animal_id = an.id
        WHERE a.usuario_id = ?
        ORDER BY a.fecha_adopcion DESC";

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
    <style>
        body {
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #E8F0FF, #F5F7FA);
        }

        .contenido {
           margin-left: 100px; /* espacio para la barra lateral */
            width: 100%;
            margin-right: 100px; /* espacio para la barra lateral */
        }

        h2 {
            margin-top: 0;
            color: #1e1e2f;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #1e1e2f;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .estado-pendiente { color: orange; font-weight: bold; }
        .estado-aprobada { color: green; font-weight: bold; }
        .estado-rechazada { color: red; font-weight: bold; }

    </style>
</head>
<body>

<div class="contenido">
    <h2>📋 Mis Adopciones</h2>

    <table>
        <tr>
            <th>Mascota</th>
            <th>Fecha de Adopción</th>
            <th>Estado</th>
            <th>Solicitud Enviada</th>
            <th>Última Actualización</th>
        </tr>
        <?php while ($fila = $resultado->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($fila['nombre_mascota']) ?></td>
                <td><?= date("d/m/Y", strtotime($fila['fecha_adopcion'])) ?></td>
                <td>
                    <?php
                    switch($fila['estado']) {
                        case 'pendiente':
                            echo "<span class='estado-pendiente'>Pendiente</span>";
                            break;
                        case 'aprobada':
                            echo "<span class='estado-aprobada'>Aprobada</span>";
                            break;
                        case 'rechazada':
                            echo "<span class='estado-rechazada'>Rechazada</span>";
                            break;
                        default:
                            echo htmlspecialchars($fila['estado']);
                    }
                    ?>
                </td>
                <td><?= date("d/m/Y H:i", strtotime($fila['creado_en'])) ?></td>
                <td><?= date("d/m/Y H:i", strtotime($fila['actualizado_en'])) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
