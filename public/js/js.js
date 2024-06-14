function actualizarActividadUsuario() {
    fetch('./includes/actualiza_actividad.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error actualizando la actividad del usuario');
            }
        })
        .catch(error => {
            console.error(error);
        });
}


function reiniciarTemporizadorInactividad() {
    clearTimeout(inactividadTimeout);
    inactividadTimeout = setTimeout(function() {
        window.location.href = '/turonvenezolano/src/includes/cerrarSesion.php'; // Redirige a una página de cierre de sesión
    }, 900000); // 5 minutos en milisegundos
}

// Función para manejar la actividad del usuario
async function manejarActividadUsuario() {
     actualizarActividadUsuario();
    reiniciarTemporizadorInactividad();
}

// Agregar eventos para detectar actividad del usuario
document.addEventListener('click', manejarActividadUsuario);
document.addEventListener('mousemove', manejarActividadUsuario);
document.addEventListener('keydown', manejarActividadUsuario); // También detectar actividad del teclado

// Establecer el temporizador inicial para la inactividad del usuario
var inactividadTimeout = setTimeout(function() {
    window.location.href = '/turonvenezolano/src/includes/cerrarSesion.php';
}, 900000); // 5 minutos en milisegundos
