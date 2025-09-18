document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.filtro-dropdown').forEach(function(dropdown){
        const toggle = dropdown.querySelector('.dropdown-toggle');
        const menu = dropdown.querySelector('.dropdown-menu');

        toggle.addEventListener('click', function(e){
            e.preventDefault();
            e.stopPropagation();
            document.querySelectorAll('.dropdown-menu').forEach(m => { if(m!==menu) m.style.display='none'; });
            menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
        });

        menu.addEventListener('click', function(e){
            e.stopPropagation();
        });
    });

    document.addEventListener('click', function(){
        document.querySelectorAll('.dropdown-menu').forEach(m => m.style.display='none');
    });
});
