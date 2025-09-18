document.addEventListener('DOMContentLoaded', function() {
    // 1. Usamos delegación de eventos en un contenedor que siempre exista.
    // 'main-container' es una buena opción, o 'document' para asegurar que funcione siempre.
    const mainContainer = document.getElementById('main-container');

    if (!mainContainer) {
        console.error('Contenedor principal no encontrado para delegación de eventos.');
        return;
    }

    // 2. Añadimos un único listener al contenedor principal.
    mainContainer.addEventListener('click', function(event) {
        // 3. Comprobamos si el elemento clicado (o su padre) es un '.like-heart'.
        const button = event.target.closest('.like-heart');

        if (!button) {
            return; // Si no se hizo clic en un corazón, no hacemos nada.
        }

        const postId = button.dataset.postId;
        const likeKey = 'liked_recurso_' + postId;

        // Re-leemos el valor del localStorage en cada clic para asegurar que la comprobación es correcta.
        let currentLikes = parseInt(localStorage.getItem(likeKey) || '0', 10);
        if (currentLikes >= 3) {
            console.log('Ya has alcanzado el límite de 3 "me gusta" para este recurso.');
            return;
        }

        const likeCountSpan = button.querySelector('.like-count');

        // Construimos la URL del nuevo endpoint de la API REST
        const apiUrl = xamle_like_object.root + 'xamle/v1/recurso/' + postId + '/like';

        // Hacemos la petición AJAX con fetch.
        fetch(apiUrl, {
            method: 'POST',
            headers: {
                'X-WP-Nonce': xamle_like_object.nonce // Enviamos el nonce en la cabecera
            }
        })
        .then(response => response.json())
        .then(response => {
            // Si la respuesta contiene 'new_count', consideramos que fue un éxito.
            if (response.new_count !== undefined) {
                // Actualizamos el contador en la pantalla.
                likeCountSpan.textContent = response.new_count;
                
                // Incrementamos el contador de "me gusta" del usuario y lo guardamos.
                currentLikes++;
                localStorage.setItem(likeKey, currentLikes.toString());

                if (currentLikes >= 3) {
                    button.classList.add('liked'); // Marcamos como "ya gustado" solo al alcanzar el límite.
                }
            } else {
                console.error('Error: ' + (response.message || 'Respuesta inesperada del servidor.'));
            }
        })
        .catch(error => {
            console.error('Error en la petición AJAX:', error);
        });
    });
});
