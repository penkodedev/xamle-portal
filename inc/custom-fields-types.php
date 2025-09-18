<?php
/**
 * Desactiva el editor de bloques (Gutenberg) para tipos de post específicos.
 *
 * @param bool   $use_block_editor Si se debe usar el editor de bloques.
 * @param string $post_type        El tipo de post que se está editando.
 * @return bool
 */
function theme_prefix_disable_gutenberg_for_cpt( $use_block_editor, $post_type ) {
    // Añade aquí los CPTs donde quieres desactivar Gutenberg.
    $disabled_post_types = array( 'recursos' ); // Comentado o vaciado para permitir Gutenberg en 'recursos'

    if ( in_array( $post_type, $disabled_post_types, true ) ) {
        return false;
    }

    return $use_block_editor;
}
add_filter( 'use_block_editor_for_post_type', 'theme_prefix_disable_gutenberg_for_cpt', 10, 2 );


/**
 * Registra los meta boxes para el CPT 'recursos'.
 */
function theme_prefix_register_recursos_meta_boxes() {
    add_meta_box(
        'recursos_pdf_metabox',                 // ID único del meta box.
        'Información Adicional',                  // Título del meta box.
        'theme_prefix_render_recursos_metabox', // Función de callback para renderizar el contenido.
        'recursos',                             // El CPT donde se mostrará.
        'advanced',                             // Contexto (advanced, normal, side).
        'high'                                  // Prioridad (high, core, default, low).
    );
}
add_action( 'add_meta_boxes', 'theme_prefix_register_recursos_meta_boxes' );


/**
 * Renderiza el contenido del meta box para los campos de PDF.
 *
 * @param WP_Post $post El objeto del post actual.
 */
function theme_prefix_render_recursos_metabox( $post ) {
    // Añadir un nonce para verificación de seguridad.
    wp_nonce_field( 'recursos_pdf_nonce_action', 'recursos_pdf_nonce' );

    // Obtener valores guardados.
    $autoria = get_post_meta( $post->ID, '_recurso_autoria', true );
    $web_url = get_post_meta( $post->ID, '_recurso_web_url', true );
    $pdf_url = get_post_meta( $post->ID, '_recurso_pdf_url', true );
    $pdf_id  = get_post_meta( $post->ID, '_recurso_pdf_id', true );
    $pdf_src = wp_get_attachment_url( $pdf_id );
    ?>
    <style>
        .recurso-field { margin-bottom: 20px; }
        .recurso-field label { display: block; font-weight: bold; margin-bottom: 5px; }
        .recurso-field input[type="url"],
        .recurso-field input[type="text"] { width: 100%; }
        .recurso-field .upload-description { font-size: 0.9em; color: #666; }
    </style>

    <div class="recurso-field">
        <label for="recurso_autoria">Autoría</label>
        <input type="text" id="recurso_autoria" name="recurso_autoria" value="<?php echo esc_attr( $autoria ); ?>" placeholder="Nombre del autor o la organización" />
    </div>

    <div class="recurso-field">
        <label for="recurso_web_url">Web del Recurso</label>
        <input type="url" id="recurso_web_url" name="recurso_web_url" value="<?php echo esc_url( $web_url ); ?>" placeholder="https://ejemplo.com/recurso" />
    </div>

    <div class="recurso-field">
        <label for="recurso_pdf_url">URL del PDF (externo)</label>
        <input type="url" id="recurso_pdf_url" name="recurso_pdf_url" value="<?php echo esc_url( $pdf_url ); ?>" placeholder="https://ejemplo.com/documento.pdf" />
    </div>

    <div class="recurso-field">
        <label for="recurso_pdf_upload">Subir PDF (desde tu PC)</label>
        <input type="hidden" name="recurso_pdf_id" id="recurso_pdf_id" value="<?php echo esc_attr( $pdf_id ); ?>" />
        <button type="button" class="button" id="upload_pdf_button">Seleccionar o Subir PDF</button>
        <button type="button" class="button" id="remove_pdf_button" style="<?php echo ( $pdf_id ? '' : 'display:none;' ); ?>">Quitar PDF</button>
        <p class="upload-description">Sube un archivo PDF o selecciónalo de la biblioteca de medios.</p>
        <div id="pdf-preview-container">
            <?php if ( $pdf_src ) : ?>
                <a href="<?php echo esc_url( $pdf_src ); ?>" target="_blank">Ver PDF actual</a>
            <?php endif; ?>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($){
        var mediaUploader;

        $('#upload_pdf_button').click(function(e) {
            e.preventDefault();
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            mediaUploader = wp.media.frames.file_frame = wp.media({
                title: 'Seleccionar un PDF',
                button: { text: 'Usar este PDF' },
                library: { type: 'application/pdf' }, // Limitar a archivos PDF
                multiple: false
            });

            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                $('#recurso_pdf_id').val(attachment.id);
                $('#pdf-preview-container').html('<a href="' + attachment.url + '" target="_blank">Ver PDF actual</a>');
                $('#remove_pdf_button').show();
            });

            mediaUploader.open();
        });

        $('#remove_pdf_button').click(function(e) {
            e.preventDefault();
            $('#recurso_pdf_id').val('');
            $('#pdf-preview-container').html('');
            $(this).hide();
        });
    });
    </script>
    <?php
}


/**
 * Guarda los datos de los meta boxes al guardar el post.
 *
 * @param int $post_id El ID del post que se está guardando.
 */
function theme_prefix_save_recursos_metadata( $post_id ) {
    // Verificar el nonce.
    if ( ! isset( $_POST['recursos_pdf_nonce'] ) || ! wp_verify_nonce( $_POST['recursos_pdf_nonce'], 'recursos_pdf_nonce_action' ) ) {
        return;
    }

    // No guardar en autoguardado.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Verificar permisos del usuario.
    if ( isset( $_POST['post_type'] ) && 'recursos' === $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    // Guardar campo Autoría.
    if ( isset( $_POST['recurso_autoria'] ) ) {
        $autoria = sanitize_text_field( $_POST['recurso_autoria'] );
        update_post_meta( $post_id, '_recurso_autoria', $autoria );
    }

    // Guardar campo Web del Recurso.
    if ( isset( $_POST['recurso_web_url'] ) ) {
        $web_url = esc_url_raw( $_POST['recurso_web_url'] );
        update_post_meta( $post_id, '_recurso_web_url', $web_url );
    }

    // Guardar campo URL.
    if ( isset( $_POST['recurso_pdf_url'] ) ) {
        $url = esc_url_raw( $_POST['recurso_pdf_url'] ); // Usar esc_url_raw para URLs
        update_post_meta( $post_id, '_recurso_pdf_url', $url );
    }

    // Guardar campo de subida de archivo.
    if ( isset( $_POST['recurso_pdf_id'] ) ) {
        $id = intval( $_POST['recurso_pdf_id'] ); // El ID es un número entero
        update_post_meta( $post_id, '_recurso_pdf_id', $id );
    }
}
add_action( 'save_post', 'theme_prefix_save_recursos_metadata' );





// ===================================================================================================
//
//                                        📌 RECURSOS
//
// ===================================================================================================
function agregar_meta_box_destacado() {
    add_meta_box(
        'meta_box_destacado',
        'Recurso Destacado',
        'mostrar_meta_box_destacado',
        array('recursos'),  // Tipos de post
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'agregar_meta_box_destacado');

// Mostrar el contenido del meta box
function mostrar_meta_box_destacado($post) {
    $destacado = get_post_meta($post->ID, 'destacado', true); // Sin guion bajo
    wp_nonce_field('guardar_destacado', 'destacado_nonce');
    ?>
    <label for="destacado_checkbox">
        <input type="checkbox" id="destacado_checkbox" name="destacado_checkbox" value="1" <?php checked($destacado, '1'); ?>>
        <span class="dashicons dashicons-star-filled" style="color: #f39c12;"></span> Destacar
    </label>
    <?php
}

// Guardar el valor del checkbox
function guardar_meta_box_destacado($post_id) {
    if (!isset($_POST['destacado_nonce']) || !wp_verify_nonce($_POST['destacado_nonce'], 'guardar_destacado')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['destacado_checkbox'])) {
        update_post_meta($post_id, 'destacado', '1'); // Sin guion bajo
    } else {
        delete_post_meta($post_id, 'destacado'); // Sin guion bajo
    }
}
add_action('save_post', 'guardar_meta_box_destacado');

// Registrar el campo en la API REST con el slug 'featured_news'
function agregar_campo_featured_news_a_api_rest() {
    register_rest_field('newsfeed', 'featured_news', array(
        'get_callback' => function($post_arr) {
            return get_post_meta($post_arr['id'], 'destacado', true);
        },
        'update_callback' => null,
        'schema' => array(
            'description' => 'Indica si la publicación es destacada',
            'type'        => 'string',
            'context'     => array('view', 'edit'),
        ),
    ));
}
add_action('rest_api_init', 'agregar_campo_featured_news_a_api_rest');

// Modificar la consulta para filtrar por "Destacado"
function filtrar_por_destacado($query) {
    global $pagenow;

    // Solo se aplica en el listado de 'newsfeed'
    if ('edit.php' === $pagenow && isset($_GET['destacado_filter']) && $_GET['destacado_filter'] !== '') {
        $destacado_filter = $_GET['destacado_filter'];

        if ($destacado_filter === '1') {
            // Filtrar por "Destacados"
            $meta_query = array(
                array(
                    'key'     => 'destacado',  // El meta key de "Destacado"
                    'value'   => '1', // Valor 1 para "Destacados"
                    'compare' => '='
                )
            );
        } elseif ($destacado_filter === '0') {
            // Filtrar por "No destacados"
            $meta_query = array(
                array(
                    'key'     => 'destacado',
                    'compare' => 'NOT EXISTS' // Buscar los que no tienen el campo 'destacado'
                ),
                'relation' => 'OR', // Permitir que también sea vacío
                array(
                    'key'     => 'destacado',
                    'value'   => '', // O el valor esté vacío
                    'compare' => '='
                )
            );
        }

        $query->set('meta_query', $meta_query);
    }
}
add_action('pre_get_posts', 'filtrar_por_destacado');
