document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modal-paquete');
    const btnCrear = document.querySelector('.boton-crear');
    const btnCerrar = document.querySelector('.cerrar');

    // Abrir modal para crear
    btnCrear.addEventListener('click', function() {
        resetearFormulario();
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    });

    // Cerrar modal
    btnCerrar.addEventListener('click', cerrarModal);
    window.addEventListener('click', function(e) {
        if (e.target === modal) cerrarModal();
    });
});

// Función global para editar paquete
window.editarPaquete = function(paquete) {
    const modal = document.getElementById('modal-paquete');
    const form = document.getElementById('form-paquete');
    
    // Rellenar el formulario con todos los campos
    form.querySelector('#nombre').value = paquete.nombre;
    form.querySelector('#descripcion').value = paquete.descripcion;
    form.querySelector('#precio').value = paquete.precio;
    form.querySelector('#duracion').value = paquete.duracion;
    form.querySelector('#imagen').value = paquete.imagen; // Añadir esta línea
    
    // Cambiar el título y el texto del botón
    modal.querySelector('h3').textContent = 'Editar Paquete';
    form.querySelector('button[type="submit"]').textContent = 'Guardar Cambios';
    
    // Añadir el ID del paquete como campo oculto
    if (!form.querySelector('#id_paquete')) {
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'id_paquete';
        idInput.id = 'id_paquete';
        form.appendChild(idInput);
    }
    form.querySelector('#id_paquete').value = paquete.id_paquete;
    
    // Cambiar action del formulario
    form.action = '../controller/actualizar_paquete.php';
    
    // Mostrar el modal
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
};

function cerrarModal() {
    const modal = document.getElementById('modal-paquete');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    resetearFormulario();
}

function resetearFormulario() {
    const form = document.getElementById('form-paquete');
    form.reset();
    form.action = '../controller/procesar_paquete.php';
    document.querySelector('#modal-paquete h3').textContent = 'Crear Nuevo Paquete';
    form.querySelector('button[type="submit"]').textContent = 'Crear Paquete';
    
    const idInput = form.querySelector('#id_paquete');
    if (idInput) idInput.remove();

    const imagenInput = form.querySelector('#imagen');
    imagenInput.required = true;
}