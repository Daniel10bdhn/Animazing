<?php
require_once "/../Config/conexion.php"; // tu conexión a BD

header('Content-Type: application/json');

if (!isset($_POST['correo']) || empty($_POST['correo'])) {
    echo json_encode(['status' => 'error', 'message' => 'Correo no recibido']);
    exit;
}

$correo = trim($_POST['correo']);

// Verificar si existe el usuario
$stmt = $conexion->prepare("SELECT id FROM usuarios WHERE correo_electronico = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Este correo no está registrado']);
    exit;
}

$user = $result->fetch_assoc();
$token = bin2hex(random_bytes(16)); // token seguro
$expira = date("Y-m-d H:i:s", strtotime("+1 hour"));

// Guardar token en la base de datos
$stmt = $conexion->prepare("INSERT INTO recuperaciones (usuario_id, token, expira) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $user['id'], $token, $expira);
$stmt->execute();

// Enviar correo (ejemplo simple con mail())
$enlace = "http://tusitio.com/restablecer_contraseña.php?token=" . $token;
$asunto = "Recuperación de contraseña";
$mensaje = "Haz clic en el siguiente enlace para restablecer tu contraseña:\n\n$enlace\n\nEste enlace expira en 1 hora.";

if (mail($correo, $asunto, $mensaje, "From: no-reply@tusitio.com")) {
    echo json_encode(['status' => 'ok', 'message' => 'Se ha enviado un enlace a tu correo.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No se pudo enviar el correo']);
}
