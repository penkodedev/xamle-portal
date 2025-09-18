<?php


//************************* INCLUDE JS FUNCTIONS **************************************

function enqueue_scripts()
{
  /*wp_enqueue_script(
    'jquery', // Unique identifier for jQuery
    get_template_directory_uri() . '/js/jquery.min.js',
    array(),
    '', // Dependencies
    true  // Load in the header
  );*/

  wp_enqueue_script(
    'prognroll-script', // Unique identifier for "prognroll"
    get_template_directory_uri() . '/js/prognroll.js',
    array('jquery'), // Dependency on jQuery
    '', // Dependencies
    true  // Load in the header
  );

  wp_enqueue_script(
    'accordion',
    get_template_directory_uri() . '/js/accordion.js',
    array('jquery'), // Dependency on jQuery
    '', // Dependencies
    true  // Load in the header
  );
  
  wp_enqueue_script(
    'secure-login',
    get_template_directory_uri() . '/js/secure-login.js',
    array(''), // Dependency on jQuery
    '', // Dependencies
    true  // Load in the header
  );

  wp_enqueue_script(
    'back-to-top-js',
    get_template_directory_uri() . '/js/back-to-top.js',
    array('jquery'), // Dependency on jQuery
    '', // Dependencies
    true  // Load in the header
  );

  wp_enqueue_script(
    'float-menu-js',
    get_template_directory_uri() . '/js/float-menu.js',
    array(),
    '', // Dependencies
    true  // Load in the header
  );

  wp_enqueue_script(
    'flickity',
    get_template_directory_uri() . '/js/flickity.pkgd.min.js',
    array(), // No necesita jQuery
    true  // Cambiamos a 'true' para cargar en el footer
  );

  wp_enqueue_script(
    'scrolling-text',
    get_template_directory_uri() . '/js/scrolling-text.js',
    array('jquery'), // Dependency on jQuery
    false  // Load in the header
  );

  wp_enqueue_script(
    'modals',
    get_template_directory_uri() . '/js/modals.js',
    array('jquery'), // Dependency on jQuery
    false  // Load in the header
  );

  wp_enqueue_script(
    'see-more',
    get_template_directory_uri() . '/js/see-more.js',
    array('jquery'), // Dependency on jQuery
    false  // Load in the header
  );

    wp_enqueue_script(
    'header-home',
    get_template_directory_uri() . '/js/header-home.js',
    array('jquery'), // Dependency on jQuery
    false  // Load in the header
  );

    wp_enqueue_script(
    'post-likes',
    get_template_directory_uri() . '/js/post-likes.js',
    array(), // Eliminamos la dependencia de jQuery que no es necesaria.
    true  // Cambiamos a 'true' para cargar el script en el footer.
  );

  // Pasar la URL de AJAX al script 'post-likes' para que pueda hacer las peticiones.
  // Esto crea el objeto 'xamle_like_object' en el frontend.
  wp_localize_script('post-likes', 'xamle_like_object', array(
      'root' => esc_url_raw(rest_url()), // La URL base de la API REST
      'nonce' => wp_create_nonce('wp_rest') // El nonce para la API REST
  ));



}
add_action('wp_enqueue_scripts', 'enqueue_scripts');
