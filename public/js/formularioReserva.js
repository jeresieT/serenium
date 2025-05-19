document.addEventListener('DOMContentLoaded', function() {
    const formReserva = document.getElementById('formReserva');
    const fechaInput = document.getElementById('fecha');
    const btnReserva = document.getElementById('btnReserva');

    if (fechaInput) {
        fechaInput.addEventListener('change', async function() {
            const fecha = this.value;
            try {
                const response = await fetch(`../controller/verificar_disponibilidad.php?fecha=${fecha}`);
                const data = await response.json();
    
                if (data.reservas >= 8) {
                    alert('Lo sentimos, este día ya tiene el máximo de reservas permitidas (8). Por favor, seleccione otra fecha.');
                    this.value = '';
                    btnReserva.disabled = true;
                } else {
                    btnReserva.disabled = false;
                }
            } catch (error) {
                console.error('Error al verificar disponibilidad:', error);
            }
        });
    }

    if (formReserva) {
        formReserva.addEventListener('submit', function(e) {
            // Prevenir múltiples envíos
            if (btnReserva.disabled) {
                e.preventDefault();
                return;
            }
            
            // Deshabilitar el botón
            btnReserva.disabled = true;
            btnReserva.innerHTML = 'Procesando...';
            
            // Permitir que el formulario se envíe
            return true;
        });
    }
});