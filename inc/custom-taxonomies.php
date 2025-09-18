<?php

// TAXONOMÍAS PERSONALIZADAS PARA EL CPT RECURSOS

function xamle_register_taxonomies() {
    
    // Nivel educativo
    register_taxonomy('nivel_educativo', 'recursos', [
        'label'        => 'Nivel educativo',
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite'      => ['slug' => 'nivel-educativo'],
    ]);

    // Áreas de conocimiento
    register_taxonomy('area_conocimiento', 'recursos', [
        'label'        => 'Áreas de conocimiento',
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite'      => ['slug' => 'area-conocimiento'],
    ]);

    // Temática principal
    register_taxonomy('tematica_principal', 'recursos', [
        'label'        => 'Temática principal',
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite'      => ['slug' => 'tematica-principal'],
    ]);

    // Población racializada referida
    register_taxonomy('poblacion_racializada', 'recursos', [
        'label'        => 'Población racializada',
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite'      => ['slug' => 'poblacion-racializada'],
    ]);

    // Tipo de recurso
    register_taxonomy('tipo_recurso', 'recursos', [
        'label'        => 'Tipo de recurso',
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite'      => ['slug' => 'tipo-recurso'],
    ]);

    // Tipo de material
    register_taxonomy('tipo_material', 'recursos', [
        'label'        => 'Tipo de material',
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite'      => ['slug' => 'tipo-material'],
    ]);

    // Idioma
    register_taxonomy('idioma', 'recursos', [
        'label'        => 'Idioma',
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite'      => ['slug' => 'idioma'],
    ]);
}

add_action('init', 'xamle_register_taxonomies');

// Crear automáticamente términos en taxonomías personalizadas
function xamle_insert_terms() {

    $tax_terms = [
        'nivel_educativo' => [
            'Secundaria 12-14',
            'Secundaria 14-16',
            'Bachillerato 16-18',
            'Formación Profesional +15',
            'Educación Superior 18+',
            'Educación de Personas Adultas 18+',
        ],
        'area_conocimiento' => [
            'Ciencias Sociales',
            'Historia',
            'Geografía',
            'Antropología',
            'Lengua',
            'Literatura',
            'Matemáticas',
            'Ciencias Naturales',
            'Biología',
            'Medio Ambiente',
            'Educación Artística',
            'Ilustración/ Artes Visuales',
            'Educación Física',
            'Cuerpo y Movimiento',
            'Artes Escénicas',
            'Música',
            'Filosofía',
            'Educación para la Ciudadanía Global',
            'Educación en Valores',
            'Educación en Derechos Humanos',
            'Orientación educativa',
            'Educación Infantil',
            'Tecnología',
            'Informática',
            'Coeducación',
            'Equidad de Género',
            'Feminismos',
            'Comunicación Audiovisual',
            'Cooperación Internacional/ Relaciones internacionales',
        ],
        'tematica_principal' => [
            'Antirracismo',
            'Racismo estructural',
            'Colonialismo / Neocolonialismo',
            'Antigitanismo',
            'Afrocentrismo',
            'Identidades afrodescendientes',
            'Islamofobia',
            'Xenofobia',
            'Epistemologías decoloniales',
            'Pensamiento decolonial',
            'Lenguaje/ Representaciones mediáticas',
            'Interseccionalidad',
            'Feminismos',
            'Historia',
            'Memoria histórica',
            'Blanquitud/ Privilegio Blanco',
            'Reparación/ Justicia racial',
        ],
        'poblacion_racializada' => [
            'Personas afrodescendientes',
            'Pueblos originarios',
            'Personas asiáticas',
            'Población gitana',
            'Población árabe',
            'Población musulmana',
            'Personas migrantes',
            'Personas refugiadas',
            'Infancias racializadas',
            'Juventudes racializadas',
            'Mujeres racializadas',
            'Personas mayores racializadas',
            'Personas LGTBIQ+ racializadas',
            'No especifica',
        ],
        'tipo_recurso' => [
            'Libros',
            'Artículo científico',
            'Artículo periodístico',
            'Artículo de opinión / ensayo breve',
            'Video corto / charla / conferencia',
            'Podcast / Audio',
            'Testimonio / Entrevista',
            'Documental',
            'Película',
            'Guía/ Manual',
            'Diccionario / Glosario',
            'Cursos y formaciones',
            'Normativa / Legislación',
        ],
        'tipo_material' => [
            'Video',
            'Texto',
            'Audio',
            'Imagen',
        ],
        'idioma' => [
           
            'Portugués (Brasil)',
            'Portugués (Portugal)',
            'Gallego',
            'Euskera',
            'Catalán',
            'Inglés',
            'Francés',
            'Italiano',
            'Alemán',
        ],
    ];

    foreach ($tax_terms as $taxonomy => $terms) {
        foreach ($terms as $term) {
            if (!term_exists($term, $taxonomy)) {
                wp_insert_term($term, $taxonomy);
            }
        }
    }
}
add_action('init', 'xamle_insert_terms');