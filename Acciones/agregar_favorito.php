<?php
session_start();
require_once __DIR__ . "/../Config/conexion.php";

header('Content-Type: application/json');

// Verificar sesión
if(!isset($_SESSION['id_usuario'])){
    echo json_encode(['status'=>'error','message'=>'Debes iniciar sesión']);
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Verificar ID de mascota recibido
if(!isset($_POST['mascota_id'])){
    echo json_encode(['status'=>'error','message'=>'ID de mascota no recibido']);
    exit;
}

$id_mascota = intval($_POST['mascota_id']);

// Verificar que la mascota existe en la tabla correcta
$stmt = $conexion->prepare("SELECT id FROM animales WHERE id = ?");
if(!$stmt){
    echo json_encode(['status'=>'error','message'=>'Error SQL: '.$conexion->error]);
    exit;
}
$stmt->bind_param("i", $id_mascota);
$stmt->execute();
$res = $stmt->get_result();

if($res->num_rows === 0){
    echo json_encode(['status'=>'error','message'=>'La mascota no existe']);
    exit;
}

// Verificar si ya está en favoritos
$stmt = $conexion->prepare("SELECT * FROM favoritos WHERE usuario_id = ? AND animal_id = ?");
if(!$stmt){
    echo json_encode(['status'=>'error','message'=>'Error SQL: '.$conexion->error]);
    exit;
}
$stmt->bind_param("ii", $id_usuario, $id_mascota);
$stmt->execute();
$res = $stmt->get_result();

if($res->num_rows > 0){
    echo json_encode(['status'=>'ok','message'=>'Ya está en tus favoritos']);
    exit;
}

// Insertar en favoritos
$stmt = $conexion->prepare("INSERT INTO favoritos (usuario_id, animal_id) VALUES (?, ?)");
if(!$stmt){
    echo json_encode(['status'=>'error','message'=>'Error SQL: '.$conexion->error]);
    exit;
}
$stmt->bind_param("ii", $id_usuario, $id_mascota);

if($stmt->execute()){
    echo json_encode(['status'=>'ok','message'=>'Añadido a favoritos']);
} else {
    echo json_encode(['status'=>'error','message'=>'No se pudo agregar: '.$stmt->error]);
}
?>
