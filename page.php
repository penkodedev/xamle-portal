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

<section class="page-title">
	<div class="page-title-container">
		<h1><?php the_title(); ?></h1>
	</div>
</section>
<main class="grid-main animate fadeIn" id="main-container">

	<article class="grid-article">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<?php the_content(); ?>
			<?php endwhile;
		else : ?>
		<?php endif; ?>
	</article>

</main>
<?php get_footer(); /* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */