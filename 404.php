<?php
/*
*
Template Name:
*
*/

get_header(); // Calls the WordPress Header

?>
    <main class="grid-main" id="main-container">
        <article class="grid-article">
            <h1>ERROR 404</h1>
        <p><?php _e( 'Al parecer este contenido no existe en nuestra Web. Intente el menú en la barra superior.', 'foo' ); ?></p>
            <p><a href="<?php echo home_url(); ?>"><?php _e( 'Volver al inicio?', 'foo' ); ?></a></p>
        </article>
    </main>
    <?php get_footer(); ?>