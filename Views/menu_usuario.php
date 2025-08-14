<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../Public/login.php");
    exit;
}

require_once __DIR__ . "/../Config/verificar_sesion.php";
require_once __DIR__ . "/../Config/conexion.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú Principal - Usuario</title>
    <link rel="stylesheet" href="../assets/Css/menu.usuario.css">
    <style>
        body {
            display: flex;
            margin: 0;
            font-family: 'Montserrat', sans-serif;
        }
        .sidebar {
            width: 220px;
            position: fixed; /* Siempre visible */
            height: 100vh;
            background-color: #1e1e2f;
            color: white;
            padding: 20px;
            box-sizing: border-box;
        }
        .sidebar .perfil {
            margin-bottom: 20px;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            margin: 10px 0;
        }
        .sidebar ul li a {
            color: white;
            text-decoration: none;
        }
        .contenido {
            margin-left: 220px; /* Deja espacio a la sidebar */
            padding: 20px;
            flex: 1;
        }
        /* Modal para Leer más */
        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.75);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 12px;
            max-width: 400px;
            width: 90%;
            position: relative;
            text-align: center;
        }
        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        .close-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="perfil">
        <p><strong><?php echo $_SESSION['usuario_nombre']; ?></strong></p>
        <span class="estado">🟢 Online</span>
    </div>
    <ul>
        <li><a href="#" data-page="menu_usuario.php">🐾 Mascotas</a></li>
        <li><a href="#" data-page="../Public/fundaciones.php">🏢 Fundaciones</a></li>
        <li><a href="#" data-page="../Public/donaciones.php">💰 Donaciones</a></li>
        <li><a href="#" data-page="../Public/favoritos.php">❤️ Mis Favoritos</a></li>
        <li><a href="#" data-page="../Public/mis_adopciones.php">📋 Mis Adopciones</a></li>
        <li><a href="#" data-page="../Public/faq.php">❓ Preguntas Frecuentes</a></li>
        <li><a href="../Public/logout.php">🚪 Cerrar Sesión</a></li>
    </ul>
</div>

<div class="contenido" id="contenido">
    <!-- Contenido cargado dinámicamente -->
    <h1>Mascotas Disponibles para Adopción</h1>
    <div class="contenedor-mascotas" id="contenedor-mascotas">
        <?php
        $query = $conexion->query("SELECT * FROM mascotas");

        while ($mascota = $query->fetch_assoc()) {
            $img_file = trim($mascota['foto']);
            $img_path = $_SERVER['DOCUMENT_ROOT'] . "/Animazing/assets/Css/img/" . $img_file;
            $img_url = "/Animazing/assets/Css/img/" . $img_file;
            if (empty($img_file) || !file_exists($img_path)) {
                $img_url = "/Animazing/assets/Css/img/default.jpg";
            }
        ?>
            <div class='tarjeta'>
                <img src="<?php echo htmlspecialchars($img_url); ?>" alt="<?php echo htmlspecialchars($mascota['nombre']); ?>">
                <h3><?php echo htmlspecialchars($mascota['nombre']); ?></h3>
                <div class="botones">
                    <button class="like" data-id="<?php echo $mascota['id']; ?>">❤️ Me gusta</button>
                    <button class="adoptar" data-id="<?php echo $mascota['id']; ?>">Adoptar 🐶</button>
                    <button class="leer" data-id="<?php echo $mascota['id']; ?>">Leer más 📖</button>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<!-- Modal Leer más -->
<div class="modal" id="infoMascotaModal">
    <div class="modal-content">
        <button class="close-btn" onclick="closeModal('infoMascotaModal')">X</button>
        <h2 id="mascotaNombre"></h2>
        <p id="mascotaDescripcion"></p>
        <p><strong>Edad:</strong> <span id="mascotaEdad"></span></p>
        <p><strong>Raza:</strong> <span id="mascotaRaza"></span></p>
    </div>
</div>

<script>
function openModal(id){ document.getElementById(id).style.display = "flex"; }
function closeModal(id){ document.getElementById(id).style.display = "none"; }

// Cargar páginas dinámicamente sin recargar sidebar
document.querySelectorAll('.sidebar a[data-page]').forEach(link => {
    link.addEventListener('click', function(e){
        e.preventDefault();
        const page = this.dataset.page;
        fetch(page)
            .then(res => res.text())
            .then(html => {
                document.getElementById('contenido').innerHTML = html;

                // Vuelve a asignar eventos a botones cargados dinámicamente
                asignarEventos();
            });
    });
});

// Función para asignar eventos de Me gusta, Adoptar y Leer más
function asignarEventos() {
    document.querySelectorAll('.like').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            fetch('../acciones/agregar_favorito.php', {
                method: 'POST',
                headers: {'Content-Type':'application/x-www-form-urlencoded'},
                body: 'mascota_id=' + encodeURIComponent(id)
            })
            .then(res => res.json())
            .then(data => alert(data.message))
            .catch(()=> alert('Error al conectar con el servidor'));
        });
    });

    document.querySelectorAll('.adoptar').forEach(btn => {
        btn.addEventListener('click', function() {
            const idMascota = this.dataset.id;
            fetch('../adoptar.php', {
                method: 'POST',
                headers: {'Content-Type':'application/x-www-form-urlencoded'},
                body: 'mascota_id=' + encodeURIComponent(idMascota)
            })
            .then(res => res.json())
            .then(data => alert(data.message));
        });
    });

    document.querySelectorAll('.leer').forEach(btn => {
        btn.addEventListener('click', function() {
            const idMascota = this.dataset.id;
            fetch('../info_mascota.php', {
                method: 'POST',
                headers: {'Content-Type':'application/x-www-form-urlencoded'},
                body: 'mascota_id=' + encodeURIComponent(idMascota)
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('mascotaNombre').textContent = data.nombre;
                document.getElementById('mascotaDescripcion').textContent = data.descripcion;
                document.getElementById('mascotaEdad').textContent = data.edad;
                document.getElementById('mascotaRaza').textContent = data.raza;
                openModal('infoMascotaModal');
            });
        });
    });
}

// Inicialmente asignar eventos a los elementos ya cargados
asignarEventos();

// Cerrar modal si clic fuera de él
window.onclick = function(event){
    if(event.target === document.getElementById('infoMascotaModal')) closeModal('infoMascotaModal');
}
</script>

</body>
</html>
