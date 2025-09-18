document.addEventListener('DOMContentLoaded', function() {
    const header = document.querySelector('.home header'); // Seleccionamos el header solo en la home

    if (!header) {
        return; // Si no estamos en la home o no hay header, no hacemos nada
    }

    function handleHeaderBackground() {
        // Solo ejecutamos esta lógica en pantallas de 576px o menos
        if (window.innerWidth <= 576) {
            const scrollPosition = window.scrollY;
            const windowHeight = window.innerHeight; // Esto es equivalente a 100vh

            // Si el scroll ha superado la altura de la ventana (la altura del slider)
            if (scrollPosition > windowHeight) {
                header.classList.add('scrolled-past-slider');
            } else {
                header.classList.remove('scrolled-past-slider');
            }
        } else {
            // En pantallas más grandes, nos aseguramos de que la clase no esté presente
            header.classList.remove('scrolled-past-slider');
        }
    }

    // Escuchamos los eventos de scroll y de cambio de tamaño de la ventana
    window.addEventListener('scroll', handleHeaderBackground);
    window.addEventListener('resize', handleHeaderBackground);
});
