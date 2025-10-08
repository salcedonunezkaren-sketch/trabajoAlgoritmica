// ==========================
// 🔍 BUSCADOR DE CONTENIDOS
// ==========================
function buscarContenido() {
    const input = document.getElementById('buscador');
    const filtro = input.value.toLowerCase().trim();
    const tarjetas = document.querySelectorAll('#informaciones .info-card'); // solo busca en informaciones
    let resultados = 0;

    tarjetas.forEach(card => {
        const titulo = card.querySelector('.card-title')?.textContent.toLowerCase() || '';
        const columna = card.closest('.tarjeta-col');

        if (titulo.includes(filtro)) {
            columna.style.display = ''; // mostrar
            resultados++;
        } else {
            columna.style.display = 'none'; // ocultar
        }
    });

    // Mostrar mensaje si no hay resultados
    let mensaje = document.getElementById('no-resultados');
    if (!mensaje) {
        mensaje = document.createElement('p');
        mensaje.id = 'no-resultados';
        mensaje.className = 'text-center text-muted mt-3';
        document.querySelector('#informaciones').appendChild(mensaje);
    }

    mensaje.textContent = (filtro && resultados === 0)
        ? `No se encontraron resultados para "${input.value}".`
        : '';
}


function redirigirTema() {
    const select = document.getElementById('select-temas');
    const url = select.value.trim();
    if (url) window.location.href = url;
}

