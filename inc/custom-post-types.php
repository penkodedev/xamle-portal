<?php

//*-------------------------------------------------
//*            RECURSOS Custom Post Type
//*-------------------------------------------------

function recursos_post_type()
{

  $supports = array(
    'title',          // Post Title
    'editor',         // Content -> Habilita un editor.
    'thumbnail',      // Featured Image
    //'excerpt',        // Field for Excerpt
    //'custom-fields',  // Native WordPress Custom fields
    //'author',         // Author of the Post
    //'trackbacks',     // Allow Trackbacks
    'revisions',      // Allow Post Revisions
    //'post-formats',   // Allow Post Formats
    //'page-attributes',// Allow Page Atributes
  );


  $labels = array(
    'name' => __('Recursos', 'textdomain'),
    'singular_name' => __('Recurso', 'textdomain'),
    'menu_name' => __('Recursos', 'textdomain'),
    'add_new' => __('Añadir Recurso', 'textdomain'),
    'add_new_item' => __('Añadir Nuevo Recurso', 'textdomain'),
    'edit_item' => __('Editar Recurso', 'textdomain'),
    'new_item' => __('Nuevo Recurso', 'textdomain'),
    'view_item' => __('Ver Recurso', 'textdomain'),
    'search_items' => __('Buscar Recursos', 'textdomain'),
    'not_found' => __('No se encontraron Recursos', 'textdomain'),
    'not_found_in_trash' => __('No se encontraron Recursos en la papelera', 'textdomain'),
    'parent_item_colon' => '',
    'all_items' => __('Todos los Recursos', 'textdomain')
  );


  $args = array(
    'supports' => $supports,
    'labels' => $labels,
    'public' => true,
    'has_archive' => true,
    'menu_icon' => 'dashicons-megaphone', // The icon that appears in the WordPress admin menu
    'rewrite' => array('slug' => 'recursos'), // URL slug
    'show_in_rest' => true, // Enable Gutenberg editor for this post type
  );

  register_post_type('recursos', $args);
}
add_action('init', 'recursos_post_type');
