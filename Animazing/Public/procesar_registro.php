<?php
require_once __DIR__ . "../Confi/conexion.php";
session_start();

$nombre = trim($_POST['nombre'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$passwordPlano = $_POST['password'] ?? '';
$tipo = $_POST['tipo'] ?? '';

// Validar campos vacíos
if (empty($nombre) || empty($correo) || empty($passwordPlano) || empty($tipo)) {
    echo "<script>alert('Todos los campos son obligatorios'); window.history.back();</script>";
    exit;
}

// Validar contraseña segura
if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $passwordPlano)) {
    echo "<script>alert('La contraseña debe tener mínimo 8 caracteres, incluir una mayúscula, una minúscula y un número'); window.history.back();</script>";
    exit;
}

// Verificar si el usuario o correo ya existen
$check = $conexion->prepare("SELECT id FROM usuarios WHERE nombre = ? OR correo_electronico = ?");
$check->bind_param("ss", $nombre, $correo);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo "<script>alert('El usuario o correo ya están registrados'); window.history.back();</script>";
    exit;
}

// Encriptar contraseña
$passwordHash = password_hash($passwordPlano, PASSWORD_DEFAULT);

// Insertar usuario
$sql = "INSERT INTO usuarios (nombre, correo_electronico, contraseña) VALUES (?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sss", $nombre, $correo, $passwordHash);

if ($stmt->execute()) {
    $usuario_id = $conexion->insert_id;

    // Asignar rol
    $rol_id = ($tipo === "admin") ? 1 : 3; // 1 = Admin, 3 = Usuario
    $sql_rol = "INSERT INTO rol_usuario (rol_id, usuario_id) VALUES (?, ?)";
    $stmt_rol = $conexion->prepare($sql_rol);
    $stmt_rol->bind_param("ii", $rol_id, $usuario_id);
    $stmt_rol->execute();

    // Iniciar sesión automáticamente
    $_SESSION['usuario_id'] = $usuario_id; // ✅ Nombre unificado
    $_SESSION['usuario_nombre'] = $nombre;
    $_SESSION['usuario_rol'] = ($tipo === "admin") ? "admin" : "user";

    // Redirigir según el tipo
    if ($tipo === "admin") {
        header("Location: dashboard_fundacion.php");
    } else {
        header("Location: dashboard_adoptar.php");
    }
    exit;
} else {
    echo "<script>alert('Error en el registro'); window.history.back();</script>";
}
?>

