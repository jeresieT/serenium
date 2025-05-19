<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - SERENIUM</title>
    <link rel="stylesheet" href="../../public/style/index.css">
    <link rel="stylesheet" href="../../public/style/contacto.css" />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="../../public/js/nav.js" defer></script>
</head>
<body>
    <header class="encabezado">
        <div class="logotipo">
          <a class="subrayado" href="index.php">
            <h2>SERENIUM</h2>
          </a>
        </div>

        <button class="menu-btn">
        <i class="fas fa-bars"></i>
    </button>
        <nav class="navegacion">
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="paquetes.php">Paquetes</a></li>
                <li><a href="contacto.php" class="activo">Contacto</a></li>
                <li><a href="login.php">Cuenta</a></li>
                <?php if (isset($_SESSION['usuario_id']) && $_SESSION['usuario_rol'] == 'admin'): ?>
          <li><a href="gestionAdministrador.php">Gestion</a></li>
        <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <section class="contacto">
            <h1>Contáctanos</h1>
            <p>Estamos aquí para ayudarte. Ponte en contacto con nosotros y reserva tu momento de relajación.</p>
            <div class="info-contacto">
                <p><strong>Teléfono:</strong> <a href="tel:+34123456789">+34 123 456 789</a></p>
                <p><strong>Email:</strong> <a href="mailto:info@serenium.com">info@serenium.com</a></p>
                <p><strong>Dirección:</strong> Calle Paz 123, Barcelona, España</p>
            </div>
            <!-- <a href="mailto:info@serenium.com" class="boton">Enviar Mensaje</a> -->
        </section>
    </main>

    <?php include_once __DIR__. '/templates/footer.php'; ?>
</body>
</html>