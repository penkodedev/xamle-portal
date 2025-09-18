<?php

/**
 * Template part para mostrar el REPRODUTOR DE AUDIO DE TEXTO A VOZ.
 *
 * Este archivo comprueba si existe un audio asociado al post actual
 * y, si es así, muestra un reproductor de audio HTML5.
 */

if (!defined('ABSPATH')) {
    exit;
}

// 1. Obtener el ID del archivo de audio desde los metadatos del post.
$audio_id = get_post_meta(get_the_ID(), '_recurso_audio_id', true);

// 2. Si existe un ID de audio, obtener su URL y mostrar el reproductor.
if ($audio_id) {
    $audio_url = wp_get_attachment_url($audio_id);
?>

    <div class="player-container">
        <p class="title-speech">Escuchar la introdución</p>
        <div class="audio-player-wrapper">
            <?php // Usamos un ID único para cada reproductor añadiendo el ID del post. ?>
            <audio id="tts-audio-<?php echo get_the_ID(); ?>" class="custom-audio-player tts-audio-player" controls src="<?php echo esc_url($audio_url); ?>"></audio>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Seleccionamos todos los reproductores por su clase en lugar de por ID.
        const audioPlayers = document.querySelectorAll('.tts-audio-player');
        
        audioPlayers.forEach(function(audio) {
            if (audio) {
                // Cuando el usuario le dé al play por primera vez en CUALQUIER reproductor, ajustamos su velocidad.
                audio.addEventListener('play', function() {
                    this.playbackRate = 1.25;
                }, { once: true });
            } 
        });
    });
    </script>
<?php
}
