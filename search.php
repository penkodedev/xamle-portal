<?php
ob_start();
/*
* Template Name: 
*/
get_header(); // Calls the WordPress Header

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>


    <h1 class="page-title">
        <?php _e( 'Resultados de la búsqueda', 'foo' ); ?>
    </h1>

    <main class="grid-main animate fadeIn" id="main-container">
        <article class="news secondady primary" id="container">
         
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <div class="search-box">
                <p class="search-link"><a href="<?php the_permalink() ?>" rel="bookmark" ><?php the_title(); ?></a></p>
                <p class="search-date"><?php the_time ('j/F/Y'); ?></p>
                <p class="search-url"><?php bloginfo('url'); ?></p>
                <p class="search-excerpt"><?php echo excerpt('29'); ?></p>
            </div>

            <?php endwhile; else: ?>
            <p class="no-post-msj"><?php _e('Lo sentimos, ninguna entrada coincide con tus criterios de búsqueda.
		Utilize el menú de la parte superior para navegar por nuestra web.', 'foo' ); ?></p>
            <?php endif; ?>

            <nav class="pagination">
                <?php echo paginate_links( array(
                    'prev_text' => __( 'anterior', 'foo' ),
                    'next_text' => __( 'siguiente', 'foo' ),
                ) ); ?>
            </nav>

        </article>
    </main>s
    <?php get_footer(); ?>