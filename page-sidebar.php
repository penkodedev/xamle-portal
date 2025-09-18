<?php
ob_start();
/*
* Template Name: Page Sidebar
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
<aside class="grid-aside">
	<?php get_sidebar(); ?>
</aside>

<main class="grid-main animate fadeIn" id="main-container">

	<?php if (function_exists('yoast_breadcrumb')) {
		yoast_breadcrumb('<p id="breadcrumbs">', '</p>');
	} ?>

	<article class="grid-article">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<?php the_content(); ?>
			<?php endwhile;
		else : ?>
		<?php endif; ?>
	</article>
</main>
<?php get_footer(); ?>