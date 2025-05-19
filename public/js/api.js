async function fetchData() {
    try {
        // Cambiamos la URL a tu servidor local
        const response = await fetch('https://www.aronacoffes.cat/apiprueba/apirest.php?api_key=123456789abcdef');
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        mostrarPromociones(data);
    } catch (error) {
        console.error('Error:', error);
        // Mostrar mensaje de error en la interfaz
        const promocionesSection = document.getElementById('promociones');
        if (promocionesSection) {
            promocionesSection.innerHTML = `
                <div class="error-mensaje">
                    <h2>Lo sentimos</h2>
                    <p>No se pudieron cargar las promociones en este momento.</p>
                </div>
            `;
        }
    }
}

function mostrarPromociones(data) {
    const promocionesSection = document.getElementById('promociones');
    if (!promocionesSection) return;

    const promociones = data.filter(item => item.en_oferta);
    
    const contenidoPromociones = `
        <h2>Promociones Especiales</h2>
        <div class="rejilla-promociones">
            ${promociones.map(promo => `
                <div class="promocion">
                    <div class="etiqueta-oferta">¡OFERTA!</div>
                    <h3>${promo.nombre}</h3>
                    <p>${promo.tipo.charAt(0).toUpperCase() + promo.tipo.slice(1)} - ${promo.duracion} minutos</p>
                    <div class="precio-promocion">
                        <p class="precio">${promo.precio}€</p>
                        <div class="valoracion">
                            <span class="estrellas">${"★".repeat(Math.round(promo.valoracion))}</span>
                            <span class="numero">${promo.valoracion}</span>
                        </div>
                    </div>
                    <a href="paquetes.php" class="boton">Reservar Ahora</a>
                </div>
            `).join('')}
        </div>
    `;
    
    promocionesSection.innerHTML = contenidoPromociones;
}

fetchData();