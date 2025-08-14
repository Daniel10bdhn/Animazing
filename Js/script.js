// Toggle menú hamburguesa
const menuToggle = document.getElementById('menu-toggle');
const navLinks = document.getElementById('nav-links');

menuToggle.addEventListener('click', () => {
    navLinks.classList.toggle('show');
});

document.querySelectorAll('.like').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;

        fetch('../acciones/agregar_favorito.php', {
            method: 'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: 'mascota_id=' + encodeURIComponent(id)
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
        })
        .catch(()=> alert('Error al conectar con el servidor'));
    });
});


