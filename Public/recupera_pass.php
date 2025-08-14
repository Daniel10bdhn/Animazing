<?php
include '../includes/db.php'; // Conexión a BD

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    $email = trim($_POST['email']);

    // Buscar usuario por correo_electronico
    $query = "SELECT nombre, correo_electronico FROM usuarios WHERE correo_electronico = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if ($fila = mysqli_fetch_assoc($resultado)) {
        $nombre = $fila['nombre'];
        $correo = $fila['correo_electronico'];

        // Generar token y fecha de expiración
        $token = bin2hex(random_bytes(32));
        $fecha_expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Guardar token y expiración
        $update_query = "UPDATE usuarios SET reset_token = ?, reset_token_expira = ? WHERE correo_electronico = ?";
        $update_stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($update_stmt, "sss", $token, $fecha_expira, $correo);
        mysqli_stmt_execute($update_stmt);

        // Preparar envío de correo
        try {
            $mail = new PHPMailer(true);
            // $mail->SMTPDebug = 3; // Para depuración

            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'sebastianguzman266@gmail.com';           // Tu correo real
            $mail->Password = 'Sebas123';         // Clave de app, NO la normal
            $mail->SMTPSecure = 'ssl';                         // O 'tls' según config
            $mail->Port = 465;                                 // O 587 si usas 'tls'
            $mail->setFrom('animazingadopp@gmail.com', 'Animazing');


            $mail->setFrom('animazingadopp@gmail.com', 'Animazing');
            $mail->addAddress($correo, $nombre);

            $mail->isHTML(true);
            $mail->Subject = 'Recuperar contraseña Animazing';

            // 💡 Enlace directo al reset_password.php con token en la URL
            $enlace = "http://localhost/Animazing/Animazing/Public/reset_password.php?token=" . $token;

            $mail->Body = "
                Hola $nombre,<br><br>
                Has solicitado restablecer tu contraseña.<br>
                Haz clic en el enlace de abajo para establecer una nueva:<br><br>
                <a href='$enlace'>Restablecer contraseña</a><br><br>
                Este enlace expirará en 1 hora.
            ";

            $mail->send();
            echo "Correo de recuperación enviado. Revisa tu bandeja de entrada.";

        } catch (Exception $e) {
            echo "Error al enviar el correo: {$mail->ErrorInfo}";
        }
    } else {
        echo "El correo electrónico no está registrado.";
    }
} else {
    echo "Por favor, envía el formulario correctamente.";
}
?>
