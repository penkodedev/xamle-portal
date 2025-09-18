/* PrognRoll | https://mburakerman.github.io/prognroll/ | @mburakerman | License: MIT */

(function ($) {
    $.fn.prognroll = function (options) {
        // Fusiona las opciones del usuario con los valores por defecto.
        var settings = $.extend({}, $.fn.prognroll.defaults, options);

        // Evita reinicializar el plugin en el mismo elemento.
        if ($(this).data("prognroll")) {
            return this;
        }
        $(this).data("prognroll", true);

        // Crea la barra de progreso una sola vez.
        var progressBar = $("<span>", { class: "prognroll-bar" }).css({
            position: "fixed",
            top: 0, // Posición fija en la parte superior
            left: 0,
            width: 0,
            height: settings.height,
            backgroundColor: settings.color,
            zIndex: 2147483647,
            transition: "width 0.2s ease-out" // Añade una transición suave al ancho
        });

        // Añade la barra al body si no existe.
        if ($(".prognroll-bar").length === 0) {
            $("body").prepend(progressBar);
        }

        // Función para actualizar el ancho de la barra de progreso.
        function updateProgressBar(scrollTop, scrollHeight, outerHeight) {
            var total = (scrollTop / (scrollHeight - outerHeight)) * 100;
            $(".prognroll-bar").css("width", total + "%");
        }

        var $scrollElement = settings.custom ? $(this) : $(window);

        // Función para manejar el evento de scroll.
        function handleScroll() {
            var scrollTop = $scrollElement.scrollTop();
            var outerHeight = settings.custom ? $scrollElement.outerHeight() : $(window).outerHeight();
            var scrollHeight = settings.custom ? $scrollElement.prop("scrollHeight") : $(document).height();
            
            updateProgressBar(scrollTop, scrollHeight, outerHeight);
        }

        $scrollElement.on("scroll.prognroll", handleScroll);

        // Calcula la posición inicial al cargar la página.
        handleScroll();

        return this;
    };

    // Valores por defecto del plugin.
    $.fn.prognroll.defaults = {
        height: 5,        // Altura de la barra en píxeles
        color: "#000", // Color de fondo de la barra
        custom: false     // Si es true, usa el elemento sobre el que se llama en lugar de la ventana.
    };

})(jQuery);

// Inicialización del plugin en el body.
jQuery(function($) {
    $("body").prognroll();
});
