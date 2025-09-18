document.addEventListener('DOMContentLoaded', function() {
    function setupModal(modalId, buttonId) {
        var modal = document.getElementById(modalId);
        var btn = document.getElementById(buttonId);
        var span = modal.getElementsByClassName("close")[0];

        if (btn && modal && span) {
            btn.onclick = function() {
                modal.classList.add('show');
                setTimeout(function() {
                    modal.style.opacity = "1";
                }, 10);
            }

            span.onclick = function() {
                modal.style.opacity = "0";
                setTimeout(function() {
                    modal.classList.remove('show');
                }, 500);
            }

            modal.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.opacity = "0";
                    setTimeout(function() {
                        modal.classList.remove('show');
                    }, 500);
                }
            }
        } else {
            console.error("Algunos elementos necesarios no se encontraron en el DOM para " + modalId + " y " + buttonId);
        }
    }

    // ADD MULTIPLE MODALS AND BUTTONS (add any modals as you wish and do not forget to change button ID too)
    var modals = [
        { modalId: "myModal",  buttonId: "openModal" },
        { modalId: "myModal2", buttonId: "openModal2" },
    ];

    modals.forEach(function(modalConfig) {
        setupModal(modalConfig.modalId, modalConfig.buttonId);
    });
});



// *********************  GIRAR ARROW ACCORDION ***********************************//
document.addEventListener('DOMContentLoaded', function () {
    const accButtons = document.querySelectorAll('.acc-button');
    
    accButtons.forEach(function (button) {
        const icon = button.querySelector('.acc-icon');
        const collapseElement = document.querySelector(button.getAttribute('data-bs-target'));

        // Evento que se activa cuando el acordeón se muestra (abre)
        collapseElement.addEventListener('shown.bs.collapse', function () {
            icon.classList.add('active');
        });

        // Evento que se activa cuando el acordeón se oculta (cierra)
        collapseElement.addEventListener('hidden.bs.collapse', function () {
            icon.classList.remove('active');
        });
    });
});
