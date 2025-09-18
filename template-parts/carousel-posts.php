<div class="main-carousel">
	<?php
	$custom_query = new WP_Query(array(
		'post_type'      => 'recursos',
		'posts_per_page' => 6, // -1 para obtener todos los recursos destacados
		'orderby'        => 'date',
		'order'          => 'DESC',
		'meta_query'     => array(
			array(
				'key'     => 'destacado',
				'value'   => '1',
				'compare' => '=',
			),
		),
	));

	if ($custom_query->have_posts()) :
		while ($custom_query->have_posts()) :
			$custom_query->the_post(); ?>

			<div class="carousel-cell">
				<?php
				// Obtenemos el contador de "me gusta" y el nonce de seguridad
				$like_count = get_post_meta(get_the_ID(), '_recurso_like_count', true);
				$like_count = !empty($like_count) ? intval($like_count) : 0;
				?>

				<div class="like-heart" data-post-id="<?php echo get_the_ID(); ?>">
					<span class="like-count"><?php echo $like_count; ?></span>
				</div>
				<div class="card-content">
					<a href="<?php the_permalink(); ?>" class="article-title"><?php the_title("<h4>", "</h4>") ?></a>
				</div>
				<p class="grid-item-excerpt" style="max-width:400px">
					<?php echo excerpt('400'); ?>
				</p>
<a href="<?php echo home_url('/recursos'); ?>" class="carousel-button"><?php _e('leer más', 'xamle'); ?> &rarr;</a>
			</div>
	<?php
		endwhile;
	else :
	// No posts found
	endif;
	wp_reset_postdata();
	?>
</div>

<div class="button-carousel">
	<a class="button" href="<?php echo esc_url( home_url( '/recursos' ) ); ?>" class="carousel-button">
  <?php _e('ver todos los recursos', 'xamle'); ?> &rarr;
</a>

</div>


<script>
	var elem = document.querySelector('.main-carousel');
	var flkty = new Flickity(elem, {

		// --- Opciones existentes ---
		autoPlay: 2300, // Mueve el carrusel automáticamente cada 2800 milisegundos (2.8 segundos).
		pauseAutoPlayOnHover: false, // El carrusel NO se detiene cuando el usuario pasa el ratón por encima.
		cellAlign: 'center', // Alinea la celda activa en el centro del carrusel.
		contain: true, // Evita que el carrusel se deslice más allá de la primera o última celda si `wrapAround` es `false`.
		prevNextButtons: false, // Oculta los botones de flecha "anterior" y "siguiente".
		pageDots: false, // Oculta los puntos de navegación inferiores.
		draggable: true, // Permite al usuario arrastrar el carrusel con el ratón o el dedo.
		freeScroll: true, // El carrusel se desliza libremente en lugar de ajustarse a una celda. Ideal para un movimiento fluido.
		wrapAround: true, // Al llegar al final, el carrusel vuelve a empezar desde el principio, creando un bucle infinito.
		rightToLeft: true, // El movimiento del carrusel es de derecha a izquierda.

		groupCells: false, // Agrupa varias celdas para que se muevan como una sola. Puede ser `true`, un número (ej. 2) o un porcentaje ('80%').
		adaptiveHeight: false, // Ajusta la altura del carrusel a la altura de la celda seleccionada. Útil si las tarjetas tienen alturas diferentes.
		fade: false, // En lugar de deslizar, las celdas aparecen y desaparecen con un efecto de fundido.
		hash: false, // Permite enlazar directamente a una celda usando un hash en la URL (ej. `pagina.html#cell-3`).
		initialIndex: 0, // Empieza en una celda específica (0 es la primera). Puede ser un número o un selector CSS.
		lazyLoad: false, // Carga las imágenes de las celdas solo cuando están a punto de ser visibles. Mejora el rendimiento.
		fullscreen: false // Permite ver el carrusel en pantalla completa.
	});
</script>