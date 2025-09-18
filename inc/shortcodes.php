<?php

//******************** GENERAL POST SHORTCODE **************************
// Crear el shortcode dinámico basado en el nombre del post type

function dynamic_post_type_shortcode($atts) {
    // Obtener el nombre del shortcode (tipo de post) desde el contexto global
    global $shortcode_tag;
    $post_type = $shortcode_tag;

    // Extraer los atributos ID y size del shortcode
    $atts = shortcode_atts(
        array(
            'id' => '',
            'size' => 'thumbnail', // Tamaño de la imagen por defecto
        ),
        $atts,
        $post_type
    );

    // Obtener el post correspondiente al ID
    $post = get_post($atts['id']);

    // Verificar si el post existe
    if (!$post) {
        return 'Elemento no encontrado';
    }

    // Obtener el contenido del post (incluyendo imágenes)
    $contenido = apply_filters('the_content', $post->post_content);

    // Devolver solo el contenido sin la imagen destacada ni el título
    return $contenido;
}

// Registrar el shortcode dinámico
function register_dynamic_post_type_shortcodes() {
    // Obtener todos los tipos de post públicos
    $post_types = get_post_types(['public' => true], 'names');
    
    foreach ($post_types as $post_type) {
        // Registrar el shortcode para cada tipo de post
        add_shortcode($post_type, 'dynamic_post_type_shortcode');
    }
}
add_action('init', 'register_dynamic_post_type_shortcodes');

// Añadir la columna del shortcode para todos los post types
function agregar_columna_shortcode_todos($columns) {
    $columns['shortcode'] = 'Shortcode';
    return $columns;
}

// Rellenar la columna con el shortcode dinámico
function mostrar_columna_shortcode_todos($column, $post_id) {
    if ($column == 'shortcode') {
        // Obtener el tipo de post del post actual
        $post_type = get_post_type($post_id);
        echo '[' . $post_type . ' id="' . $post_id . '"]';
    }
}

// Hacer la columna del shortcode ordenable (opcional)
function hacer_columna_shortcode_ordenable_todos($columns) {
    $columns['shortcode'] = 'shortcode';
    return $columns;
}

// Hook general para todos los tipos de post públicos
function aplicar_shortcodes_a_todos_los_post_types() {
    // Obtener todos los post types públicos
    $post_types = get_post_types(['public' => true], 'names');
    
    foreach ($post_types as $post_type) {
        // Añadir la columna del shortcode a cada tipo de post
        add_filter("manage_{$post_type}_posts_columns", 'agregar_columna_shortcode_todos');
        // Rellenar la columna del shortcode
        add_action("manage_{$post_type}_posts_custom_column", 'mostrar_columna_shortcode_todos', 10, 2);
        // Hacer la columna del shortcode ordenable
        add_filter("manage_edit-{$post_type}_sortable_columns", 'hacer_columna_shortcode_ordenable_todos');
    }
}
add_action('admin_init', 'aplicar_shortcodes_a_todos_los_post_types');



/*************** CUSTOM ACCORDION ***********************/
function custom_accordion_item_shortcode($atts, $content = null) {
    // Obtén los atributos del shortcode
    $atts = shortcode_atts(
        array(
            'title' => '',
            'accordion_id' => '', // Agregamos el atributo accordion_id
        ),
        $atts,
        'accordion_item'
    );

    // Genera un identificador único para el elemento del acordeón si no se proporciona uno
    $accordion_item_id = 'accordion_item_' . uniqid();

    // Si no se proporciona accordion_id, genera uno automáticamente
    if (empty($atts['accordion_id'])) {
        $atts['accordion_id'] = 'accordion_' . uniqid();
    }

    // Envuelve el contenido del elemento del acordeón en un panel
    $output = '<div class="acc-item">';
    $output .= '<div class="acc-header" id="' . esc_attr($accordion_item_id) . '">';
    $output .= '<div class="acc-button" type="button" data-bs-toggle="collapse" data-bs-target="#' . esc_attr($accordion_item_id) . '-content" aria-expanded="true" aria-controls="' . esc_attr($accordion_item_id) . '-content">';
    $output .= esc_html($atts['title']);
    $output .= '<span class="acc-icon"></span>'; // Usa el carácter de flecha hacia abajo
    $output .= '</div>';
    $output .= '</div>';
    $output .= '<div id="' . esc_attr($accordion_item_id) . '-content" class="acc-collapse collapse" aria-labelledby="' . esc_attr($accordion_item_id) . '" data-bs-parent="#' . esc_attr($atts['accordion_id']) . '">';
    $output .= '<div class="acc-body">';
    $output .= do_shortcode($content);
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';

    return $output;
}
add_shortcode('accordion_item', 'custom_accordion_item_shortcode');





//******************** NEWS CPT SHORTCODE **************************
function news_shortcode($atts)
{
    $atts = shortcode_atts(array(
        'count' => -1, // Adjust on shortcode [news count="3"]
    ), $atts);

    $args = array(
        'post_type' => 'news',
        'post_status' => 'publish',
        'posts_per_page' => $atts['count'],
    );

    $loop = new WP_Query($args);

    ob_start(); // Start output buffering
    ?>
    <!-- BEGIN SHORTCODE CONTENT -->
    <div class="post-grid">
        <?php while ($loop->have_posts()) : $loop->the_post(); ?>
            <div class="post-col col-4">
                <div class="grid-item">
                    <figure><a title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail('large'); ?></a>
                    </figure>
                    <div class="grid-item-content">
                        <h5>
                            <?php the_title(); ?>
                        </h5>
                        <p class="grid-item-excerpt">
                            <?php echo excerpt('24'); ?>
                        </p>
                        <a class="button" href="<?php the_permalink(); ?>"><?php _e('leer más', 'penkode' ); ?></a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <!-- END SHORTCODE CONTENT -->
    <?php
    wp_reset_postdata(); // Reset the query to the main loop

    return ob_get_clean(); // Return the buffered content
}

add_shortcode('news', 'news_shortcode'); // SHORTCODE USAGE [news count="3"] if you want 3 last



//******************** ANY WIDGET SHORTCODE [widget="Widget Name"] **************************
// Function to display a widget by name
function display_widget_by_name($atts) {
  // Extract the widget name from the shortcode attributes
  $widget_name = isset($atts[0]) ? sanitize_text_field($atts[0]) : '';

  // Check if the widget name is provided
  if (empty($widget_name)) {
      return 'Widget name is required.';
  }

  // Get the sidebar ID based on the widget name
  $sidebar_id = sanitize_title($widget_name);

  // Check if the sidebar with the provided ID exists
  if (is_active_sidebar($sidebar_id)) {
      ob_start();
      dynamic_sidebar($sidebar_id);
      $widget_content = ob_get_clean();
      return $widget_content;
  } else {
      return 'It seems like Widget do not exists or is do not have any content. Check your widget please in Appearance > Widgets';
  }
}

// Add a shortcode to display widgets by name
add_shortcode('widget', 'display_widget_by_name'); // SHORTCODE USAGE [widget="Widget Name"]



//**************** LIST CHILD PAGES ********************
function list_child_pages() {
  global $post;

  if (is_page() && $post->post_parent)
      $childpages = wp_list_pages('sort_column=menu_order&title_li=&child_of=' . $post->post_parent . '&echo=0');
  else
      $childpages = wp_list_pages('sort_column=menu_order&title_li=&child_of=' . $post->ID . '&echo=0');

  if ($childpages) {
      $string = '<div class="child-pages-section">' . $childpages . '</div>'; // CONTAINER DIV
      return $string;
  }
}
add_shortcode('wpb_childpages', 'list_child_pages'); //USAGE SHORTCODE [list_child_pages]
