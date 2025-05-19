document.addEventListener('DOMContentLoaded', function() {
    let paqueteBasico = document.getElementById('paqueteBasica')
    let botonBasico = document.getElementById('botonBasica');
    let infoBasico = document.getElementById('infoBasica')

    let paqueteProfundo = document.getElementById('paqueteProfunda')
    let botonProfundo = document.getElementById('botonProfunda');
    let infoProfundo = document.getElementById('infoProfunda')

    let paqueteCompleto = document.getElementById('paqueteCompleto')
    let botonCompleto = document.getElementById('botonCompleto');
    let infoCompleto = document.getElementById('infoCompleto')

    //evento de click para cuando le de a la etiqueta de boton muestre lo que se esta creando en el div vacio del html 
    botonBasico.addEventListener('click', function(){
        //De la primera descripcion, creo la etiqueta del html y el texto, esto lo muestro en el div vacio 'infoBasico'
        let descripcionPaquete = document.createElement('p')
        descripcionPaquete.textContent = 'Masaje sueco de 60 minutos'
        infoBasico.appendChild(descripcionPaquete)

        //Segunda descripcion inicializada, creo la etiqueta y el texto y lo meto al div vacio
        let descripcionPaquete2 = document.createElement('p')
        descripcionPaquete2.textContent= 'Aromaterapia incluida'
        infoBasico.appendChild(descripcionPaquete2)

        //Creo la etiqueta del texto, creo la etiqueta, el texto y le agrego la clase reutulizando el boton de ver mas, por ultimo lo muestro en el contenedor vacio 
        let precioPaquete = document.createElement('p')
        precioPaquete.textContent = '50€'
        precioPaquete.classList.add('precio')
        infoBasico.appendChild(precioPaquete)


        //Boton Reservar, creo la etiqueta, el texto y reutilizo la clase, depues lo muestro en el contenedor vacio creado 
        //Y para que no se pmuestre el boton de Ver mas le doy un estilo
        let botonReservar = document.createElement('a');
        botonReservar.textContent = 'Reservar';
        botonReservar.classList.add('boton');
        botonReservar.href = `formulario_reservas.php?paquete=${encodeURIComponent(descripcionPaquete.textContent)}&precio=${encodeURIComponent(precioPaquete.textContent)}`;
        infoBasico.appendChild(botonReservar)
        botonBasico.style.display = 'none'
    } )

    botonProfundo.addEventListener('click', function(){
        let descripcionPaquete = document.createElement('p')
        descripcionPaquete.textContent = 'Masaje de tejido profundo 90 min'
        infoProfundo.appendChild(descripcionPaquete)

        let descripcionPaquete2 = document.createElement('p')
        descripcionPaquete2.textContent= 'Piedras calientes'
        infoProfundo.appendChild(descripcionPaquete2)

        let precioPaquete = document.createElement('p')
        precioPaquete.textContent = '80€'
        precioPaquete.classList.add('precio')
        infoProfundo.appendChild(precioPaquete)

        let botonReservar = document.createElement('a');
        botonReservar.textContent = 'Reservar';
        botonReservar.classList.add('boton');
        botonReservar.href = `formulario_reservas.php?paquete=${encodeURIComponent(descripcionPaquete.textContent)}&precio=${encodeURIComponent(precioPaquete.textContent)}`;
        infoProfundo.appendChild(botonReservar)
        botonProfundo.style.display = 'none'
    } )

    botonCompleto.addEventListener('click', function(){
        let descripcionPaquete = document.createElement('p')
        descripcionPaquete.textContent = 'Masaje completo 120 min'
        infoCompleto.appendChild(descripcionPaquete)

        let descripcionPaquete2 = document.createElement('p')
        descripcionPaquete2.textContent= 'Exfoliación corporal'
        infoCompleto.appendChild(descripcionPaquete2)

        let precioPaquete = document.createElement('p')
        precioPaquete.textContent = '120€'
        precioPaquete.classList.add('precio')
        infoCompleto.appendChild(precioPaquete)

        let botonReservar = document.createElement('a');
        botonReservar.textContent = 'Reservar';
        botonReservar.classList.add('boton');
        botonReservar.href = `formulario_reservas.php?paquete=${encodeURIComponent(descripcionPaquete.textContent)}&precio=${encodeURIComponent(precioPaquete.textContent)}`;
        infoCompleto.appendChild(botonReservar)
        botonCompleto.style.display = 'none'
    }); 
})