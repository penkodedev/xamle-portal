<?php
// Obtenemos dinámicamente todas las taxonomías asociadas al CPT 'recursos'
$taxonomies_objects = get_object_taxonomies('recursos', 'objects');
$taxonomias = [];
foreach ($taxonomies_objects as $tax_slug => $tax_object) {
    // Usamos el 'name' como slug y 'label' como el texto visible
    $taxonomias[$tax_object->name] = $tax_object->label;
}
// Recoger filtros activos
$active_filters = [];
foreach ($taxonomias as $tax_slug => $tax_label) {
    // Para selects no múltiples, el valor no es un array.
    $active_filters[$tax_slug] = !empty($_GET[$tax_slug]) ? $_GET[$tax_slug] : '';
}
?>

<form method="get" class="recursos-filtros-form">
    <?php foreach ($taxonomias as $tax_slug => $tax_label) :
        $terms = get_terms(['taxonomy' => $tax_slug, 'hide_empty' => true]);
        if (!empty($terms) && !is_wp_error($terms)) : ?>

            <div class="filtro-select">
                <select name="<?php echo esc_attr($tax_slug); ?>" id="<?php echo esc_attr($tax_slug); ?>">
                    <option value=""><?php echo esc_html($tax_label); ?></option>
                    <?php foreach ($terms as $term) :
                        // Comparamos directamente el slug con el filtro activo.
                        $selected = ($active_filters[$tax_slug] === $term->slug) ? 'selected' : '';
                    ?>
                        <option value="<?php echo esc_attr($term->slug); ?>" <?php echo $selected; ?>>
                            <?php echo esc_html($term->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

    <?php endif;
    endforeach; ?>

    <div class="filtro-botones">
        <button type="submit" class="button buscar">Buscar</button>
        <a href="<?php echo get_post_type_archive_link('recursos'); ?>" class="button limpiar">Limpiar selección</a>
    </div>
</form>



<?php
// --- Lógica para mostrar el resumen de la búsqueda ---
global $wp_query;
$active_filters_for_display = [];
$has_active_filters = false;

// 1. Comprobar si hay algún filtro activo en la URL
foreach ($taxonomias as $tax_slug => $tax_label) {
    if (!empty($_GET[$tax_slug])) {
        $has_active_filters = true;
        break; // Solo necesitamos saber si hay al menos uno
    }
}

// 2. Si hay filtros activos Y hay resultados, mostrar el mensaje
if ($has_active_filters && $wp_query->have_posts()) {
    // Recorremos los posts del resultado para ver qué términos tienen
    $found_terms = [];
    while ($wp_query->have_posts()) {
        $wp_query->the_post();
        foreach ($taxonomias as $tax_slug => $tax_label) {
            if (!empty($_GET[$tax_slug]) && has_term($_GET[$tax_slug], $tax_slug)) {
                $term = get_term_by('slug', $_GET[$tax_slug], $tax_slug);
                if ($term && !isset($found_terms[$term->term_id])) {
                    $found_terms[$term->term_id] = '<a href="' . esc_url(get_term_link($term)) . '">' . esc_html($term->name) . '</a>';
                }
            }
        }
    }
    rewind_posts(); // Rebobinamos el loop para que la rejilla de posts se muestre correctamente

    if (!empty($found_terms)) {
?>
        <div class="recursos-summary">

            <?php
            printf(
                _n('Mostrando %d recurso con los filtros:', 'Mostrando %d recursos con los filtros:', $wp_query->found_posts, 'xamle'),
                $wp_query->found_posts
            );
            ?>
            <strong><?php echo implode('   •   ', $found_terms); ?></strong>

        </div>
<?php
    }
}
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Busca el formulario de filtros por su clase
        const form = document.querySelector('.recursos-filtros-form');

        // Si el formulario no existe en la página, no hacemos nada
        if (!form) {
            return;
        }

        // Busca todos los 'select' dentro de ese formulario
        const selects = form.querySelectorAll('select');

        // Para cada 'select', escucha el evento 'change'
        selects.forEach(function(select) {
            select.addEventListener('change', function() {
                // Muestra el spinner y el overlay
                const spinnerOverlay = document.getElementById('filtro-spinner-overlay');
                if (spinnerOverlay) {
                    spinnerOverlay.style.display = 'flex';
                }
                // Cuando un 'select' cambia, envía el formulario automáticamente
                form.submit();
            });
        });

        // Busca el botón de limpiar por su clase
        const cleanButton = form.querySelector('.button.limpiar');

        // Si el botón existe, escucha el evento 'click'
        if (cleanButton) {
            cleanButton.addEventListener('click', function() {
                // Muestra el spinner y el overlay antes de navegar
                const spinnerOverlay = document.getElementById('filtro-spinner-overlay');
                if (spinnerOverlay) {
                    spinnerOverlay.style.display = 'flex';
                }
                // La navegación a la URL del href ocurrirá de forma natural después de esto.
            });
        }
    });
</script>