<?php
/**
 * Template part for displaying post metadata in single views.
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
        'poblacion_racializada',
        'nivel_educativo',
		//'idioma',
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
	<hr class="meta-separator-line">

    <div class="recursos-container">
	<?php
	// 2. Mostrar el enlace a la web del recurso.
	$web_url = get_post_meta( get_the_ID(), '_recurso_web_url', true );
	if ( ! empty( $web_url ) ) :
		?>
		<div class="meta-item recurso-web-link">
				<strong><a href="<?php echo esc_url( $web_url ); ?>" target="_blank" rel="noopener noreferrer"><?php _e( 'Página Web →', 'xamle' ); ?> </a></strong>
		</div>
        
		<?php
	endif;
	// 3. Mostrar el enlace al PDF (que ya tiene su propia estructura interna).
	get_template_part( 'template-parts/show-pdf' );
	?>
    </div>

</div>