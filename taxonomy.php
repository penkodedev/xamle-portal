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

<section class="page-title tax">
	<h1>Recursos</h1>
	<?php
	// Muestra un subtítulo dinámico con el nombre de la categoría (término) que se está viendo.
	if (is_tax()) {
		echo '<p class="term-name">Mostrando recursos de la categoría <strong>' . single_term_title('', false) . '</strong></p>';
	}
	?>
</section>

<main class="grid-main animate fadeIn" id="main-container">

	<div id="filtro-spinner-overlay" class="filtro-spinner-overlay" style="display: none;">
		<div class="filtro-spinner"></div>
	</div>
	<h3>Filtros de búsqueda</h3>
	<?php get_template_part('template-parts/recursos-filter'); ?>
	<?php get_template_part('template-parts/grid-posts'); ?>

	<?php if (!have_posts()) : ?>
		<p class="no-post-msj"><?php _e('Lo sentimos, ningún recurso coincide con tus criterios de búsqueda.<br>Intente de nuevo con otras opciones de búsqueda.', 'xamle'); ?></p>
	<?php endif; ?>

	<nav class="pagination">
		<?php echo paginate_links( array(
            'prev_text' => __( 'anterior', 'xamle' ),
            'next_text' => __( 'siguiente', 'xamle' ),
        ) ); ?>
	</nav>

</main>
<?php get_footer(); ?>