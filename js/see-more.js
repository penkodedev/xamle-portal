document.addEventListener('DOMContentLoaded', function() {
    const containers = document.querySelectorAll('.see-more-container');
    
    if (containers.length === 0) {
        console.error('No containers found');
    }
  
    containers.forEach(container => {
        const toggleBtn = container.querySelector('.toggle-btn');
        const textContainer = container.querySelector('.text-container');
        const toggleText = container.querySelector('.toggle-text');
  
        if (!toggleBtn || !textContainer || !toggleText) {
            console.error('Missing elements in container:', container);
            return;
        }
  
        toggleBtn.addEventListener('click', () => {
            textContainer.classList.toggle('expanded');
            toggleText.textContent = textContainer.classList.contains('expanded') ? 'Ver menos' : 'Ver más';
        });
    });
  });