/************* BACK TO TOP ********************/
jQuery(document).ready(function () {
  var offset = 500;
  var duration = 600;
  
  // Inicialmente oculto
  jQuery(".back-to-top").css({
    'right': '-100px',
    'opacity': '0',
    'transition': 'right 0.6s ease-in-out, opacity 0.6s ease-in-out'
  });

  jQuery(window).scroll(function () {
    if (jQuery(this).scrollTop() > offset) {
      // Mostrar el botón con animación
      jQuery(".back-to-top").css({
        'right': '20px',
        'opacity': '1'
      });
    } else {
      // Ocultar el botón con animación
      jQuery(".back-to-top").css({
        'right': '-100px',
        'opacity': '0'
      });
    }
  });

  jQuery(".back-to-top").click(function (event) {
    event.preventDefault();
    jQuery("html, body").animate({ scrollTop: 0 }, duration);
    return false;
  });
});