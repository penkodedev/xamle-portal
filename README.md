# XAMLE: Portal de Recursos Antirracistas (Tema de WordPress)

Este repositorio contiene el tema de WordPress a medida para el portal de recursos antirracistas de **XAMLE**. El tema está construido desde cero, con un fuerte enfoque en la personalización y el rendimiento, evitando la dependencia de plugins de terceros para sus funcionalidades clave.

---

## ✨ Características Principales

Este tema ha sido desarrollado con un gran número de implementaciones a medida para ofrecer una experiencia única y optimizada, **minimizando el uso de plugins externos**.

###  Funcionalidades Ad Hoc (Sin Plugins)

*   **Buscador Avanzado con Filtros Dinámicos**:
    *   Un sistema de filtrado completo en el frontend que permite a los usuarios buscar recursos combinando múltiples taxonomías (área de conocimiento, idioma, nivel educativo, etc.).
    *   La lógica de filtrado se maneja directamente en el backend con `pre_get_posts`, permitiendo consultas complejas con relación `OR` entre taxonomías.
    *   Actualización de resultados vía AJAX para una experiencia de usuario fluida, con un spinner de carga visual.

*   **Sistema de "Me Gusta" (Likes)**:
    *   Implementación de una funcionalidad de "Me Gusta" para los recursos.
    *   Utiliza un endpoint de la **API REST de WordPress** personalizado para registrar los likes de forma asíncrona.
    *   El contador de likes se almacena como un campo personalizado (`_recurso_like_count`) en cada recurso.
    *   Control en el lado del cliente (vía `localStorage`) para evitar que un mismo usuario dé múltiples "Me Gusta" al mismo recurso en una sesión.

*   **Generación de Audio (Text-to-Speech)**:
    *   Generación automática de un archivo de audio (MP3) a partir del título y contenido de un recurso cada vez que se publica o actualiza.
    *   Utiliza la API no oficial de Google Translate TTS mediante peticiones `wp_remote_get` para máxima compatibilidad.
    *   Detecta el idioma del recurso a través de la taxonomía "Idioma" para generar el audio en la lengua correcta (español, portugués, inglés, etc.).
    *   Los audios se guardan automáticamente en una carpeta dedicada (`/uploads/mp3/`) y se asocian al post correspondiente en la Biblioteca de Medios.

*   **Gestión de Contenido a Medida**:
    *   **Custom Post Type (CPT) "Recursos"**: Creado desde cero para gestionar todo el catálogo de materiales.
    *   **Taxonomías Personalizadas**: Más de 7 taxonomías (`area_conocimiento`, `nivel_educativo`, `idioma`, etc.) para una clasificación granular y potente de los recursos.
    *   **Campos Personalizados (Meta Boxes)**: Creados sin plugins para añadir información adicional como autoría, enlaces a webs externas o subida de PDFs directamente desde el editor del post.

*   **Mejoras en el Panel de Administración**:
    *   **Columna "Destacado" con Interruptor (Toggle)**: Permite marcar un recurso como "destacado" directamente desde el listado, usando AJAX para una actualización instantánea sin recargar la página.
    *   Ocultación de los audios generados automáticamente de la vista principal de la Biblioteca de Medios para mantenerla limpia.

---

## 🛠️ Stack Tecnológico

*   **CMS**: WordPress
*   **Lenguaje**: PHP
*   **Estilos**: SCSS compilado a CSS
*   **Dependencias PHP**: Composer
*   **Base de Datos**: MySQL / MariaDB

---

## 🚀 Instalación y Configuración

1.  **Clonar el Repositorio**: Clona este tema dentro de tu directorio `wp-content/themes/` en una instalación de WordPress.
    ```sh
    git clone [URL_DEL_REPOSITORIO] xamle-theme
    ```

2.  **Instalar Dependencias de Composer**: Navega al directorio del tema y ejecuta Composer para instalar las librerías PHP necesarias.
    ```sh
    cd wp-content/themes/xamle-theme
    composer install
    ```

3.  **Activar el Tema**: Ve al panel de administración de WordPress (`Apariencia > Temas`) y activa el tema.

4.  **Permalinks**: Ve a `Ajustes > Enlaces Permanentes` y asegúrate de que no esté seleccionada la opción "Simple". Se recomienda "Nombre de la entrada" para que los CPTs y taxonomías funcionen correctamente. Guarda los cambios para regenerar las reglas de reescritura.

---

## 📁 Estructura del Tema

El tema sigue una estructura organizada para facilitar el mantenimiento:

```
/xamle-backend-theme
├── /inc/                  # Lógica principal del tema
│   ├── api.php            # Endpoints de la API REST
│   ├── custom-post-types.php # Registro de CPTs (Recursos, Preguntas, etc.)
│   ├── custom-fields-types.php # Metaboxes y campos personalizados
│   ├── custom-taxonomies.php # Registro de Taxonomías
│   ├── dashboard.php      # Personalizaciones del panel de admin
│   ├── security.php       # Medidas de seguridad
│   └── text-to-speech.php # Lógica de generación de audio
├── /js/                   # Archivos JavaScript
├── /scss/                 # Archivos SASS/SCSS
├── /template-parts/       # Fragmentos de plantillas reutilizables
├── /vendor/               # Dependencias de Composer
├── functions.php          # Archivo principal de funciones del tema
├── style.css              # Hoja de estilos principal
└── README.md              # Este archivo
```

---

## 🤝 Contribuciones

Las contribuciones son bienvenidas. Para cambios importantes, por favor, abre un *issue* primero para discutir lo que te gustaría cambiar.

1.  Haz un Fork del proyecto.
2.  Crea tu rama de funcionalidad (`git checkout -b feature/AmazingFeature`).
3.  Confirma tus cambios (`git commit -m 'Add some AmazingFeature'`).
4.  Haz Push a la rama (`git push origin feature/AmazingFeature`).
5.  Abre una Pull Request.

---

## 📄 Licencia

Distribuido bajo la Licencia MIT. Ver `LICENSE` para más información.

---

## 📞 Contacto

**MAD África** - https://madafrica.es/

Desarrollado por [Tu Nombre/Empresa]

```

Este `README.md` está en Markdown y es muy completo. Explica qué hace el proyecto, qué tecnologías usa, cómo instalarlo y la estructura de archivos. ¡Espero que te sea de gran ayuda!

<!--
[PROMPT_SUGGESTION]¿Podrías generar un archivo `LICENSE` con la licencia MIT para este proyecto?[/PROMPT_SUGGESTION]
[PROMPT_SUGGESTION]Basado en el `README.md`, ¿qué pasos debería seguir para desplegar este backend de WordPress en un servidor de producción?[/PROMPT_SUGGESTION]
