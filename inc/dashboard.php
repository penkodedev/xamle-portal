
<?php

//************************* DISABLE dashboard full screen mode **************************
if (is_admin()) {
  function disable_editor_fullscreen_by_default()
  {
    $script = "jQuery( window ).load(function() { const isFullscreenMode = wp.data.select( 'core/edit-post' ).isFeatureActive( 'fullscreenMode' );
    if ( isFullscreenMode ) { wp.data.dispatch( 'core/edit-post' ).toggleFeature( 'fullscreenMode' ); } });";
    wp_add_inline_script('wp-blocks', $script);
  }
  add_action('enqueue_block_editor_assets', 'disable_editor_fullscreen_by_default');
}

//******REMOVE certain PLUGINS UPDATE ***********
function remove_update_notifications($value)
{

  if (isset($value) && is_object($value)) {
    unset($value->response['smart-slider-3/nextend-smart-slider3-pro.php']);
    unset($value->response['akismet/akismet.php']);
  }

  return $value;
}
add_filter('site_transient_update_plugins', 'remove_update_notifications');


//************************* REMOVE Dashboard menu items **************************************
function remove_menus()
{

  //remove_menu_page( 'index.php' ); //**************************//Dashboard Home and Updates
  remove_menu_page('edit.php'); //*****************************//Posts
  //remove_menu_page('edit.php?post_type=portfolio'); //*********//Portfolio CPT
  remove_menu_page('edit-comments.php'); //**********************//Comments
  //remove_menu_page( 'edit.php?post_type=page' ); //************//Pages
  //remove_menu_page( 'themes.php' ); //*************************//Appearance
  //remove_menu_page( 'plugins.php' ); //************************//Users
  //remove_menu_page( 'tools.php' ); //**************************//Tools
  //remove_menu_page( 'options-general.php' ); //****************//Settings
}
add_action('admin_menu', 'remove_menus');



// ===================================================================================================
//
//                      ⭐ COLUMNA "DESTACADO" CON QUICK TOGGLE PARA RECURSOS
//
// ===================================================================================================

/**
 * 1. Añade la columna "Destacado" al listado de Recursos.
 */
function xamle_add_destacado_column($columns) {
    // Añade la columna 'destacado' al final del array de columnas.
    $columns['destacado'] = '<span class="dashicons dashicons-star-filled" style="color: #f39c12; font-size: 16px; vertical-align: middle;" title="Destacado"></span>';
    return $columns;
}
add_filter('manage_recursos_posts_columns', 'xamle_add_destacado_column');

/**
 * 2. Muestra el contenido de la columna "Destacado" (el interruptor).
 */
function xamle_display_destacado_column($column, $post_id) {
    if ($column === 'destacado') {
        $is_destacado = get_post_meta($post_id, 'destacado', true) == '1';
        // Nonce para la seguridad de la petición AJAX
        $nonce = wp_create_nonce('xamle_toggle_destacado_nonce');
        ?>
        <label class="switch">
            <input type="checkbox" 
                   class="destacado-toggle" 
                   data-post-id="<?php echo $post_id; ?>" 
                   data-nonce="<?php echo $nonce; ?>"
                   <?php checked($is_destacado); ?>>
            <span class="slider round"></span>
        </label>
        <?php
    }
}
add_action('manage_recursos_posts_custom_column', 'xamle_display_destacado_column', 10, 2);

/**
 * 3. Encola el CSS y JS necesarios en el panel de administración.
 */
function xamle_admin_enqueue_scripts($hook) {
    // Solo cargar en la página de edición de 'recursos'
    if ('edit.php' !== $hook || get_post_type() !== 'recursos') {
        return;
    }

    // CSS para el interruptor
    $css = "
        .switch { position: relative; display: inline-block; width: 34px; height: 20px; vertical-align: middle; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; }
        .slider:before { position: absolute; content: ''; height: 14px; width: 14px; left: 3px; bottom: 3px; background-color: white; transition: .4s; }
        input:checked + .slider { background-color: #f39c12; }
        input:checked + .slider:before { transform: translateX(14px); }
        .slider.round { border-radius: 22px; }
        .slider.round:before { border-radius: 50%; }
        .column-destacado { width: 60px; text-align: center !important; }
    ";
    wp_add_inline_style('wp-admin', $css);

    // JavaScript para manejar el AJAX
    $js = "
        jQuery(document).ready(function($) {
            $('.destacado-toggle').on('change', function() {
                var checkbox = $(this);
                var post_id = checkbox.data('post-id');
                var nonce = checkbox.data('nonce');
                var is_checked = checkbox.is(':checked');

                // Deshabilitar temporalmente para evitar clics múltiples
                checkbox.prop('disabled', true);

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'xamle_toggle_destacado',
                        post_id: post_id,
                        is_destacado: is_checked,
                        _ajax_nonce: nonce
                    },
                    success: function(response) {
                        // Reactivar el checkbox
                        checkbox.prop('disabled', false);
                        if (!response.success) {
                            // Si falla, revertir el estado visual
                            checkbox.prop('checked', !is_checked);
                            alert('Hubo un error al actualizar el estado.');
                        }
                    },
                    error: function() {
                        checkbox.prop('disabled', false);
                        checkbox.prop('checked', !is_checked);
                        alert('Error de conexión.');
                    }
                });
            });
        });
    ";
    wp_add_inline_script('jquery-core', $js);
}
add_action('admin_enqueue_scripts', 'xamle_admin_enqueue_scripts');

/**
 * 4. La función PHP que maneja la petición AJAX.
 */
function xamle_handle_toggle_destacado() {
    // Verificar nonce y permisos
    if (
        !check_ajax_referer('xamle_toggle_destacado_nonce', '_ajax_nonce', false) ||
        !current_user_can('edit_posts')
    ) {
        wp_send_json_error(['message' => 'Permiso denegado.'], 403);
    }

    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    $is_destacado = isset($_POST['is_destacado']) && $_POST['is_destacado'] === 'true';

    if ($post_id > 0) {
        if ($is_destacado) {
            update_post_meta($post_id, 'destacado', '1');
        } else {
            delete_post_meta($post_id, 'destacado');
        }
        wp_send_json_success();
    } else {
        wp_send_json_error(['message' => 'ID de post no válido.'], 400);
    }
}
add_action('wp_ajax_xamle_toggle_destacado', 'xamle_handle_toggle_destacado');

/**
 * 5. (Opcional) Hacer la columna ordenable.
 */
function xamle_make_destacado_column_sortable($columns) {
    $columns['destacado'] = 'destacado';
    return $columns;
}
add_filter('manage_edit-recursos_sortable_columns', 'xamle_make_destacado_column_sortable');


// ===================================================================================================
//
//                      MEDIA LIBRARY: Ocultar archivos de la carpeta /mp3/
//
// ===================================================================================================
/**
 * Oculta los archivos de la carpeta /uploads/mp3/ de la Biblioteca de Medios de WordPress.
 */
function hide_generated_audio_from_media_library($query) {
    global $pagenow;

    // Solo en la biblioteca (pantalla de medios o AJAX de adjuntos)
    if ($pagenow !== 'upload.php' && ! defined('DOING_AJAX')) {
        return $query;
    }

    // Si el usuario está filtrando específicamente audios, no tocamos la query
    if (isset($_REQUEST['post_mime_type']) && $_REQUEST['post_mime_type'] === 'audio') {
        return $query;
    }

    // Excluir los archivos que estén en la carpeta "mp3/"
    $query['meta_query'][] = [
        'key'     => '_wp_attached_file',
        'value'   => 'mp3/',
        'compare' => 'NOT LIKE',
    ];

    return $query;
}
add_filter('ajax_query_attachments_args', 'hide_generated_audio_from_media_library');

