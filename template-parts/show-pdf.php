<?php
/**
 * Template part para mostrar el enlace de descarga de PDF para un recurso.
 *
 * Este template busca primero un PDF subido localmente y, si no lo encuentra,
 * busca una URL de PDF externa. Muestra un enlace si encuentra cualquiera de los dos.
 *
 * @package xamle-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Usamos el objeto global $post para obtener el ID de forma fiable,
// incluso dentro de loops complejos o llamadas AJAX.
global $post;
if ( ! $post ) {
	return; // Si no hay un objeto $post, no hacemos nada.
}
$post_id = $post->ID;

// Obtener el ID del PDF subido y la URL externa.
$pdf_id          = get_post_meta( $post_id, '_recurso_pdf_id', true );
$pdf_url_externa = get_post_meta( $post_id, '_recurso_pdf_url', true );

$final_pdf_url = '';

if ( ! empty( $pdf_id ) ) {
    // Prioridad 1: Usar el PDF subido.
    $final_pdf_url = wp_get_attachment_url( $pdf_id );
} elseif ( ! empty( $pdf_url_externa ) ) {
    // Prioridad 2: Usar la URL externa.
    $final_pdf_url = $pdf_url_externa;
}

// Si tenemos una URL final, mostramos el enlace.
if ( ! empty( $final_pdf_url ) ) : ?>
<div class="pdf-download-link">

    <strong><a href="<?php echo esc_url( $final_pdf_url ); ?>"  target="_blank" rel="noopener noreferrer">
        <?php _e( 'Descargar Documentación →', 'xamle' ); ?> 
    </a></strong>
    
    </div>
<?php
endif;
// Es CRUCIAL restaurar los datos del post original después de terminar.
wp_reset_postdata();
?>