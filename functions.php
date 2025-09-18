<?php

//******************** Enqueue necesary files from includes folder **************************
require_once get_template_directory() . '/inc/dashboard.php';
require_once get_template_directory() . '/inc/include-js.php';
require_once get_template_directory() . '/inc/custom-post-types.php';
require_once get_template_directory() . '/inc/custom-fields-types.php';
require_once get_template_directory() . '/inc/custom-taxonomies.php';
require_once get_template_directory() . '/inc/shortcodes.php';
require_once get_template_directory() . '/inc/register-widgets.php';
require_once get_template_directory() . '/inc/register-nav-menus.php';

require_once get_template_directory() . '/vendor/autoload.php'; // Autoloader de Composer
require_once get_template_directory() . '/inc/text-to-speech.php'; // Funcionalidad de Texto a Voz

//require_once get_template_directory() . '/inc/security.php';

//************ */ 1. Register the last login date, time, and count the number of logins.
function record_last_login_time($user_login, $user) {
  // Update last login time
  update_user_meta($user->ID, 'last_login', current_time('mysql'));

  // Count number of logins
  $login_count = get_user_meta($user->ID, 'login_count', true);
  if (!$login_count) {
      $login_count = 0;
  }
  update_user_meta($user->ID, 'login_count', ++$login_count);
}
add_action('wp_login', 'record_last_login_time', 10, 2);

//************ */ 2. Add columns to display the last login date and the number of logins.
function add_custom_user_columns($columns) {
  $columns['last_login'] = __('Last Login');
  $columns['login_count'] = __('Login Count');
  return $columns;
}
add_filter('manage_users_columns', 'add_custom_user_columns');

//************ */ 3. Display the last login date and number of logins in the new columns.
function display_custom_user_columns($value, $column_name, $user_id) {
  if ($column_name == 'last_login') {
      $last_login = get_user_meta($user_id, 'last_login', true);
      if ($last_login) {
          return date('d/m/Y H:i:s', strtotime($last_login)); // Latin format: day/month/year
      } else {
          return __('Never');
      }
  }

  if ($column_name == 'login_count') {
      $login_count = get_user_meta($user_id, 'login_count', true);
      if ($login_count) {
          return $login_count;
      } else {
          return __('0');
      }
  }

  return $value;
}
add_action('manage_users_custom_column', 'display_custom_user_columns', 10, 3);

//************ */ 4. Make both columns sortable.
function make_custom_user_columns_sortable($columns) {
  $columns['last_login'] = 'last_login';
  $columns['login_count'] = 'login_count';
  return $columns;
}


//******************** Allow FEATURED IMAGES on CPTs  **************************
function custom_theme_features()  {
    // Add theme support for Featured Images
    add_theme_support( 'post-thumbnails' );
}
// Hook into the 'after_setup_theme' action
add_action( 'after_setup_theme', 'custom_theme_features' );


//******************** Allow to upload and display WEBP images  **************************
add_filter('mime_types', function($existing_mimes) {
  $existing_mimes['webp'] = 'image/webp';
  return $existing_mimes;
});

/* Display WebP thumbnail*/
add_filter('file_is_displayable_image', function($result, $path) {
  return ($result) ? $result : (empty(@getimagesize($path)) || !in_array(@getimagesize($path)[2], [IMAGETYPE_WEBP]));
}, 10, 2);


//******************** DUPLICATE POSTS/PAGES/CUSTOM POSTS on Dashboard  **************************

function duplicate_post_link($actions, $post) {
  if (current_user_can('edit_posts')) {
      if (post_type_supports($post->post_type, 'editor')) {
          $actions['duplicate'] = '<a href="' . esc_url(wp_nonce_url(admin_url('admin-post.php?action=duplicate_post&post=' . $post->ID), 'duplicate-post_' . $post->ID)) . '" title="Duplicate this item" rel="permalink">Duplicate</a>';
      }
  }
  return $actions;
}

function duplicate_post_action() {
  if (isset($_GET['post']) && isset($_GET['action']) && $_GET['action'] == 'duplicate_post') {
      $post_id = absint($_GET['post']);
      if (current_user_can('edit_posts')) {
          $new_post_id = duplicate_post($post_id);
          if (!is_wp_error($new_post_id)) {
              wp_redirect(admin_url('post.php?action=edit&post=' . $new_post_id));
              exit;
          } else {
              wp_die('Error duplicating the post.');
          }
      }
  }
}

function duplicate_post($post_id) {
  $post = get_post($post_id);
  if (isset($post) && $post != null) {
      $args = array(
          'post_title' => $post->post_title,
          'post_content' => $post->post_content,
          'post_excerpt' => $post->post_excerpt,
          'post_status' => $post->post_status,
          'post_type' => $post->post_type,
          'post_author' => get_current_user_id(),
      );
      $new_post_id = wp_insert_post($args);
      if ($new_post_id) {
        
          // Duplicate post meta information (custom fields)
          $post_meta = get_post_custom($post_id);
          foreach ($post_meta as $key => $value) {
              if ($key != '_edit_lock' && $key != '_edit_last') {
                  foreach ($value as $meta_value) {
                      add_post_meta($new_post_id, $key, maybe_unserialize($meta_value));
                  }
              }
          }
          return $new_post_id;
      }
  }
  return new WP_Error('duplicate-error', 'Could not duplicate the post.');
}

add_filter('page_row_actions', 'duplicate_post_link', 10, 2);
add_filter('post_row_actions', 'duplicate_post_link', 10, 2);
add_action('admin_init', 'duplicate_post_action');



//******************** ADD CUSTOM POSTS to "At a Glance" Widget **************************
function add_custom_post_types_to_at_a_glance($items) {
  $custom_post_types = get_post_types(array('public' => true, '_builtin' => false), 'objects');

  foreach ($custom_post_types as $post_type) {
      $num_posts = wp_count_posts($post_type->name);
      $num = number_format_i18n($num_posts->publish);
      $text = _n($post_type->labels->singular_name, $post_type->labels->name, intval($num_posts->publish));
      $items[] = sprintf('<a class="%1$s-count" href="edit.php?post_type=%1$s">%2$s %3$s</a>', $post_type->name, $num, $text);
  }

  return $items;
}

add_filter('dashboard_glance_items', 'add_custom_post_types_to_at_a_glance');


//******************** Handle the AJAX request for Advanced Search **************************
function search_news() {
  $genre = $_GET['genre'];
  $rock = isset($_GET['rock']) ? $_GET['rock'] : '';

  // Perform the query based on the filters
  $args = array(
    'post_type' => 'news',
    'posts_per_page' => 3,
    'paged' => isset($_GET['page']) ? $_GET['page'] : 1,
    'meta_query' => array(
      'relation' => 'AND',
      array(
        'key' => 'genre',
        'value' => $genre,
        'compare' => 'LIKE',
      ),
      array(
        'key' => 'rock',
        'value' => $rock,
        'compare' => '=',
      ),
    ),
  );
  $query = new WP_Query($args);

  // Create an empty array to store the results
  $results = array();

  // Process the query results
  if ($query->have_posts()) {
    while ($query->have_posts()) {
      $query->the_post();
      // Generate HTML for each post
      $post_html = '<h2>' . get_the_title() . '</h2>' . '<p>' . get_the_content() . '</p>';
      // Append the post HTML to the results array
      $results[] = $post_html;
    }
  }

  // Restore the global post data
  wp_reset_postdata();

  // Return the results as JSON
  wp_send_json_success($results);
}
add_action('wp_ajax_search_news', 'search_news');
add_action('wp_ajax_nopriv_search_news', 'search_news');


/******************** Change default POST NAME (when needed) **************************
add_filter( 'post_type_labels_post', 'change_post_labels' );
function change_post_labels( $args ) {
    foreach( $args as $key => $label ){
        $args->{$key} = str_replace( [ __( 'Posts' ), __( 'Post' ) ],
        __( 'Name' ), $label ); // change post name

    }
    return $args;
}*/

//************************* LINE AWESOME support **************************************
function enqueue_stylesheets()
{
  wp_enqueue_style('line-awesome', get_stylesheet_directory_uri() . '/assets/fonts/_line-awesome/css/line-awesome.css');
}
add_action('wp_enqueue_scripts', 'enqueue_stylesheets');


//************************* FONTAWESOME support **************************************
function enqueue_our_required_stylesheets()
{
  wp_enqueue_style('font-awesome', get_stylesheet_directory_uri() . '/assets/fonts/_fontawesome/css/font-awesome.css');
}
add_action('wp_enqueue_scripts', 'enqueue_our_required_stylesheets');



//******************** Add Post Type & Post Name to Body Class **************************
add_filter('body_class', 'add_post_class');
function add_post_class($classes)
{
  global $post;
  if (isset($post)) {
    $classes[] = $post->post_type . ' ' . $post->post_name;
  }
  return $classes;
}
//******************** Add Class to submenu MEGAMENU **************************
function change_ul_item_classes_in_nav($classes, $args, $depth)
{

  if (0 == $depth) {
    $classes[] = 'level-sub1';
    $classes[] = 'megamenu-container';
    $classes[] = 'animate';
    $classes[] = 'fadeIn'; // AOS anmimation
  }
  if (1 == $depth) { // change sub-menu depth
    $classes[] = 'level-sub2';
  }
  if (2 == $depth) { // change sub-menu depth
    $classes[] = 'mega-menu-column';
  }

  return $classes;
}
add_filter('nav_menu_submenu_css_class', 'change_ul_item_classes_in_nav', 10, 3);


//************************* WPML current link language class *****************************
function custom_language_selector()
{
  $languages = icl_get_languages('skip_missing=0&orderby=custom&order=desc');
  if (1 < count($languages)) {
    foreach ($languages as $l) {
      //adds the class "current_language" if the language that is being viewed.
      $current = $l['active'] ? ' class="current_language"' : '';
      $langs[] = '<a' . $current . ' href="' . $l['url'] . '">' . $l['native_name'] . '</a>';
    }
    echo join(' / ', $langs);
  }
}

//************************* YOUTUBE/VIMEO 100% WIDTH ********************
add_theme_support('responsive-embeds');

//************** REMOVE WORD "Category:" from category pages titles ******************
function prefix_category_title($title)
{
  if (is_category()) {
    $title = single_cat_title('', false);
  }
  return $title;
}
add_filter('get_the_archive_title', 'prefix_category_title');


//************** REMOVE WORD "Archive:" from archive pages titles ******************
add_filter('get_the_archive_title', 'archive_title_remove_prefix');
function archive_title_remove_prefix($title)
{
  if (is_post_type_archive()) {
    $title = post_type_archive_title('', false);
  }
  return $title;
}


//************************* LIMIT OR DISABLE WP REVISIONS *****************************
//define('MY_CUSTOM_POST_REVISIONS', 3); // Limit to 3 revisions
//define('WP_POST_REVISIONS', false); // Disable revisions

//************************* REMOVE WP EMOJI **************************************
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');


//************************* Apply CSS clases on FIRST & LAST MENU ITEMS **************************************
function add_first_and_last($items)
{
  $items[1]->classes[] = 'first-item';
  $items[count($items)]->classes[] = 'last-item';
  return $items;
}
add_filter('wp_nav_menu_objects', 'add_first_and_last');


//*************************** NUMBER OF SEARCH RESULTS ****************************************
function change_wp_search_size($query)
{
  if ($query->is_search)
    $query->query_vars['posts_per_page'] = 10;

  return $query;
}
add_filter('pre_get_posts', 'change_wp_search_size');


//******************************* Custom Excerpt lengths *************************************************
function excerpt($limit)
{
    // Usamos wp_strip_all_tags para limpiar el contenido de forma segura,
    // manteniendo el texto pero eliminando HTML y shortcodes.
    // Usamos el objeto global $post para obtener el contenido de forma más fiable.
    global $post;
    $content = $post->post_content;

    $excerpt = wp_strip_all_tags( $content, true );
    
    // Comprobamos si la longitud del extracto es mayor que el límite.
    if (mb_strlen($excerpt) > $limit) {
        // Cortamos el texto hasta el límite.
        $excerpt = mb_substr($excerpt, 0, $limit);
        // Buscamos el último espacio para no cortar palabras por la mitad.
        $last_space = mb_strrpos($excerpt, ' ');
        $excerpt = mb_substr($excerpt, 0, $last_space);
        $excerpt .= '...';
    }
    return $excerpt;
}

/******************************** MODAL WINDOWS **********************************/
function my_custom_shortcode($atts) {
  ob_start();
  ?>


  <!-- Modal content BEGIN -->
  <span id="modal-open"></span>
  <div id="modal-window" class="modal">
      <div class="modal-content animate fadeInRight">
          <span class="modal-close">&times;</span>
          <?php echo do_shortcode('[wcpf_filters id="242"]'); ?>   <!-- Use shortcodes on get_template_part -->
      </div>
  </div>
  <!-- Modal content END -->

<script>
    // Get the modal
    var modal = document.getElementById("modal-window");

    // Get the button that opens the modal
    var btn = document.getElementById("modal-open");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("modal-close")[0];

    // When the user clicks on the button, open the modal
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>


  <?php
  return ob_get_clean();
}
add_shortcode('my_modal_shortcode', 'my_custom_shortcode');


// Filtro simple OR para recursos
function xamle_recursos_filter_or($query) {
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('recursos')) {
        
        $tax_query = array('relation' => 'OR');
        $has_filters = false;
        
        $taxonomies = get_object_taxonomies('recursos');
        
        foreach ($taxonomies as $taxonomy) {
            if (!empty($_GET[$taxonomy])) {
                $has_filters = true;
                $tax_query[] = array(
                    'taxonomy' => $taxonomy,
                    'field'    => 'slug',
                    'terms'    => sanitize_text_field($_GET[$taxonomy]),
                );
            }
        }
        
        if ($has_filters) {
            $query->set('tax_query', $tax_query);
        }
    }
}
add_action('pre_get_posts', 'xamle_recursos_filter_or');


//******************** ELIMINAR ESTILOS GLOBALES INLINE DE WORDPRESS **************************
// function xamle_remove_global_styles() {
//     remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
//     remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');
// }
// add_action('init', 'xamle_remove_global_styles');


//******************** FUNCIONALIDAD DE "ME GUSTA" PARA RECURSOS **************************

function xamle_register_like_route() {
    register_rest_route('xamle/v1', '/recurso/(?P<id>\d+)/like', array(
        'methods' => 'POST',
        'callback' => 'xamle_handle_like_recurso',
        'permission_callback' => function ($request) {
            // Verificación de Nonce para seguridad
            $nonce = $request->get_header('X-WP-Nonce');
            if (!wp_verify_nonce($nonce, 'wp_rest')) {
                return new WP_Error('rest_nonce_invalid', 'Nonce inválido.', array('status' => 403));
            }
            return true;
        },
    ));
}
add_action('rest_api_init', 'xamle_register_like_route');

function xamle_handle_like_recurso(WP_REST_Request $request) {
    $post_id = intval($request['id']);

    // Comprobar si el post existe y es del tipo 'recursos'
    if (get_post_type($post_id) !== 'recursos') {
        return new WP_Error('invalid_post', 'El ID del post no es válido.', array('status' => 404));
    }

    // Obtener el contador actual de "me gusta"
    $like_count = get_post_meta($post_id, '_recurso_like_count', true);
    
    // Si no existe, lo inicializamos a 0. Si existe, nos aseguramos de que sea un número.
    $new_like_count = !empty($like_count) ? intval($like_count) + 1 : 1;

    // Actualizar el valor en la base de datos
    $update_result = update_post_meta($post_id, '_recurso_like_count', $new_like_count);

    if ($update_result === false) {
        return new WP_Error('update_failed', 'No se pudo actualizar el contador.', array('status' => 500));
    }

    // Devolver la respuesta correcta con el nuevo total
    return new WP_REST_Response(['new_count' => $new_like_count], 200);
}