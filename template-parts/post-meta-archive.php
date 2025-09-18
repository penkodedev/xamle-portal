<?php
/**
 * Template part for displaying post metadata in archive views.
 *
 * @package xamle-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<div class="post-meta">
	<?php
	// DEFINE LAS CATEGORIAS QUE SE QUIERE MOSTRAR
	$taxonomies_a_mostrar = [
		'area_conocimiento',
		'tematica_principal',
		'idioma',
	];

	// 1. Mostrar las taxonomías
	foreach ( $taxonomies_a_mostrar as $taxonomy_slug ) :
		$taxonomy = get_taxonomy( $taxonomy_slug );
		if ( ! $taxonomy ) { continue;
		}

		// get_the_term_list devuelve los términos con sus enlaces
		$term_list = get_the_term_list( get_the_ID(), $taxonomy_slug, '', '<span class="meta-separator">•</span>' );

		if ( $term_list && ! is_wp_error( $term_list ) ) : ?>
			<div class="meta-item taxonomy-group <?php echo esc_attr( $taxonomy_slug ); ?>">
				<strong class="meta-label"><?php echo esc_html( $taxonomy->label ); ?>:</strong>
				<span class="meta-value"><?php echo $term_list; ?></span>
			</div>
		<?php endif;
	endforeach;
	?>
</div>