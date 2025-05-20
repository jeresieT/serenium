document.addEventListener('DOMContentLoaded', function() {
    const inputTelefono = document.getElementById('telefono');
    
    inputTelefono.addEventListener('input', function() {
        // Eliminar cualquier carácter que no sea número
        this.value = this.value.replace(/[^0-9]/g, '');
        
        // Limitar a 9 dígitos
        if (this.value.length > 9) {
            this.value = this.value.slice(0, 9);
        }
        
        // Validar longitud
        if (this.value.length < 9) {
            this.setCustomValidity('El teléfono debe tener 9 dígitos');
        } else {
            this.setCustomValidity('');
        }
    });
});