<?php
require_once __DIR__ . "/Config/conexion.php";
session_start();

$usuario = $_POST['usuario'] ?? '';
$password = $_POST['password'] ?? '';
$tipo = $_POST['tipo'] ?? '';

if (empty($usuario) || empty($password) || empty($tipo)) {
    echo "<script>alert('Todos los campos son obligatorios'); window.history.back();</script>";
    exit;
}

$sql = "SELECT u.*, LOWER(r.nombre_guardado) AS rol
        FROM usuarios u
        JOIN rol_usuario ru ON u.id = ru.usuario_id
        JOIN roles r ON ru.rol_id = r.id
        WHERE u.correo_electronico = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();

    // Verificar contraseña (hash o texto plano)
    $password_correcta = false;
    if (password_verify($password, $fila['contraseña']) || $password === $fila['contraseña']) {
        $password_correcta = true;
    }

    if ($password_correcta) {
        $tipo_form = strtolower(trim($tipo));
        $rol_db = strtolower(trim($fila['rol']));

        if (($tipo_form === "admin" && $rol_db === "admin") ||
            ($tipo_form === "usuario" && in_array($rol_db, ["usuario", "user"]))) {

            $_SESSION['id_usuario'] = $fila['id'];
            $_SESSION['usuario_nombre'] = $fila['nombre'];
            $_SESSION['usuario_rol'] = $rol_db;

            if ($tipo_form === "usuario") {
                header("Location: Views/menu_usuario.php");
            } else {
                header("Location: Views/dashboard_fundacion.php");
            }
            exit;
        } else {
            echo "<script>alert('No tienes permisos para este tipo de inicio de sesión'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Contraseña incorrecta'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Usuario no encontrado'); window.history.back();</script>";
}
