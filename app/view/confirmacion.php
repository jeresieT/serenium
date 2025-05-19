
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación - SERENIUM</title>
    <link rel="stylesheet" href="../../public/style/index.css">
    <link rel="stylesheet" href="../../public/style/confirmacionReserva.css">
</head>
<body>

    <main>
        <?php if (isset($_GET['message']) && $_GET['message'] === 'full'): ?>
            <div class="mensaje-error">
                <h2>Lo sentimos</h2>
                <p>El día seleccionado ya tiene el máximo de reservas permitidas (8). Por favor, seleccione otra fecha.</p>
                <a href="javascript:history.back()" class="boton">Volver al formulario</a>
            </div>
        <?php elseif (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
            <div class="mensaje-confirmacion">
                <h2>Ha ocurrido un error</h2>
                <p class="mensaje-error">Lo sentimos, no hemos podido procesar tu reserva.</p>
                <p>Por favor, inténtalo de nuevo más tarde o contáctanos directamente.</p>
                <a href="index.php" class="boton">Volver al inicio</a>
            </div>
        <?php else: ?>
            <div class="mensaje-confirmacion">
                <h2>¡Reserva Confirmada!</h2>
                <p class="mensaje-exito">
                    <strong>Hemos recibido tu reserva correctamente.</strong></p>
                <p>Te hemos enviado un email con los detalles de tu reserva.</p>
                <p>Nos pondremos en contacto contigo pronto para confirmar la cita.</p>
                <a href="index.php" class="boton">Volver al inicio</a>
            </div>
        <?php endif; ?>
    </main>
    
</body>
</html>