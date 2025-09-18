<?php
ob_start();
/*
* Template Name: 
*/
get_header(); // Calls the WordPress Header

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<section class="page-title">
    <h1><?php
        // Get the post_type
        $post_type = $post->post_type;

        // Pluralize the post_type name
        $pluralized_post_type = $post_type . ''; // This assumes that adding 's' to the end is enough for pluralization

        // Capitalize the first letter
        $capitalized_post_type = ucfirst($pluralized_post_type);

        // Print the result
        echo $capitalized_post_type;
        ?></h1>
</section>


<aside class="grid-aside">
    <?php get_sidebar(); ?>
</aside>

<main class="grid-main animate fadeIn" id="main-container">
    <article class="grid-article">

        <?php
        // Obtenemos el contador de "me gusta" y el nonce de seguridad
        $like_count = get_post_meta(get_the_ID(), '_recurso_like_count', true);
        $like_count = !empty($like_count) && intval($like_count) > 0 ? intval($like_count) : '';
        ?>
        <div class="like-heart" data-post-id="<?php echo get_the_ID(); ?>">
            <span class="like-count"><?php echo $like_count; ?></span>
        </div>

        <?php
        if ('recursos' === get_post_type()) :
            $archive_link = get_post_type_archive_link('recursos');
        ?>
            <div class="back-link"><a href="<?php echo esc_url($archive_link); ?>"><?php _e('&larr; Volver a los recursos', 'foo'); ?></a></div>
        <?php endif; ?>

        <h1><?php the_title(); ?></h1>

        <?php
        // Solo mostrar el reproductor y los metadatos si es un 'recurso'.
        if ('recursos' === get_post_type()) :
        ?>

            <?php get_template_part( 'template-parts/post-meta-single' ); ?>

            <?php get_template_part('template-parts/player-speech'); ?>
        <?php endif; // Fin de la comprobación de 'recursos' 
        ?>


        <figure class="post-image">
            <?php //the_post_thumbnail('large'); 
            ?>
        </figure>
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <?php the_content(); ?>
            <?php endwhile;
        else : ?>
        <?php endif; ?>

        <?php
        // Muestra la autoría si existe
        $autoria = get_post_meta(get_the_ID(), '_recurso_autoria', true);
        if (!empty($autoria)) : ?>
            <p class="recurso-autoria"><strong>Autoría:</strong> <?php echo esc_html($autoria); ?></p>
        <?php endif; ?>
    </article>


    <nav id="nav-single">
        <div class="pag-previous"><?php previous_post_link('%link', __('&larr; Anterior', 'foo')); ?></div>
        <div class="pag-next"><?php next_post_link('%link', __('Siguiente &rarr;', 'foo')); ?></div>
    </nav>
</main>
<?php get_footer(); ?>