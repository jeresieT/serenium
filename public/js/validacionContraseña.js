
document.addEventListener('DOMContentLoaded', function() {
    const inputContraseña = document.getElementById('contraseña');
    const requisitos = {
        longitud: 8,
        mayuscula: /[A-Z]/,
        minuscula: /[a-z]/,
        numero: /[0-9]/,
        especial: /[^A-Za-z0-9]/
    };

    function validarContraseña(contraseña) {
        const comprobaciones = {
            longitud: contraseña.length >= requisitos.longitud,
            mayuscula: requisitos.mayuscula.test(contraseña),
            minuscula: requisitos.minuscula.test(contraseña),
            numero: requisitos.numero.test(contraseña),
            especial: requisitos.especial.test(contraseña)
        };

        return comprobaciones;
    }

    inputContraseña.addEventListener('input', function() {
        const contraseña = this.value;
        const comprobaciones = validarContraseña(contraseña);

        // Actualitzar missatge de validació
        let mensaje = '';
        if (!comprobaciones.longitud) mensaje += '- Minimo 8 caracteres<br>';
        if (!comprobaciones.mayuscula) mensaje += '- Una letra mayúscula<br>';
        if (!comprobaciones.minuscula) mensaje += '- Una letra minúscula<br>';
        if (!comprobaciones.numero) mensaje += '- Un número<br>';
        if (!comprobaciones.especial) mensaje += '- Un caracter especial<br>';

        const divFeedback = document.getElementById('password-feedback');
        if (mensaje) {
            divFeedback.innerHTML = '<div class="requisitos-contraseña">Requisitos pendientes:<br>' + mensaje + '</div>';
        } else {
            divFeedback.innerHTML = '<div class="contraseña-valida">Contraseña valida</div>';
        }
    });
});