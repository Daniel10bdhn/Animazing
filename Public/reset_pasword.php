<?php
include '../includes/db.php'; // Ajusta la ruta si es necesario

// Validar que el token venga por GET
if (!isset($_GET['token'])) {
    die('Token no especificado.');
}

$token = $_GET['token'];

// Verificar que el token exista en la base y no haya expirado
$query = "SELECT correo_electronico FROM usuarios WHERE reset_token = ? AND reset_token_expira > NOW()";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $token);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$row = mysqli_fetch_assoc($result)) {
    die('Token inválido o expirado.');
}

// Procesar formulario cuando se envía por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar que existan las claves en $_POST
    if (isset($_POST['password']) && isset($_POST['password_confirm'])) {
        $password = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];

        // Validaciones
        if ($password !== $password_confirm) {
            echo "Las contraseñas no coinciden.";
        } elseif (strlen($password) < 6) {
            echo "La contraseña debe tener al menos 6 caracteres.";
        } else {
            // Hashear la nueva contraseña
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);

            // Actualizar en base de datos y limpiar el token
            $update = "UPDATE usuarios 
                       SET password = ?, reset_token = NULL, reset_token_expira = NULL 
                       WHERE correo_electronico = ?";
            $update_stmt = mysqli_prepare($conn, $update);
            mysqli_stmt_bind_param($update_stmt, "ss", $password_hashed, $row['correo_electronico']);
            mysqli_stmt_execute($update_stmt);

            if (mysqli_stmt_affected_rows($update_stmt) > 0) {
                echo "Contraseña actualizada correctamente. Ahora puedes iniciar sesión.";
                exit;
            } else {
                echo "Error al actualizar la contraseña.";
            }
        }
    } else {
        echo "Por favor completa todos los campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar Contraseña</title>
</head>
<body>
    <h2>Cambiar contraseña</h2>
    <form method="POST">
        <label>Nueva contraseña:</label><br>
        <input type="password" name="password" required><br><br>

        <label>Confirmar contraseña:</label><br>
        <input type="password" name="password_confirm" required><br><br>

        <button type="submit">Guardar nueva contraseña</button>
    </form>
</body>
</html>
