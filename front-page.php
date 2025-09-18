<?php
ob_start();
/*
* Template Name: Index Full Width
*/
get_header(); // Calls the WordPress Header

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

 <?php if (is_front_page()) {
    get_template_part('/template-parts/home-slider');
  } else {
  }
  ?>
  
<main class="grid-main animate fadeIn" id="main-container">

	<section class="section-01">
		<article id="article">
  			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <?php the_content(); ?>
            <?php endwhile;
        else : ?>
        <?php endif; ?>
		</article>
	</section>
	
	
	<section class="section-02">
		<article id="article">
				<h1 class="recursos-title">Recursos destacados</h1>
	<?php get_template_part('/template-parts/carousel-posts'); ?>
		</article>
	</section>

</main>

<?php get_footer(); ?>




