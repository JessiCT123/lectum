function abrirUI(html) {
    const modal = document.getElementById('ui-modal');
    const content = document.getElementById('ui-content');
    
    content.innerHTML = html;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Pequeño delay para la animación de entrada
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function cerrarUI() {
    const modal = document.getElementById('ui-modal');
    const content = document.getElementById('ui-content');
    
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 200);
}