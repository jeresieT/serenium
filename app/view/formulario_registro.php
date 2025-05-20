<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SERENIUM</title>
    <link rel="stylesheet" href="../../public/style/index.css" />
    <link
      href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="../../public/style/login.css" />  

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
          <li><a href="index.php" >Inicio</a></li>
          <li><a href="paquetes.php">Paquetes</a></li>
          <li><a href="contacto.php">Contacto</a></li>
          <li><a href="login.php" class="activo">Cuenta</a></li>
        </ul>
      </nav>
    </header>

    <main>
        <div class="formulario-login">
            <h2>Registrate</h2>
            
            <?php
// Mostrar mensaje solo si el error es de email
if (isset($_GET['error']) && $_GET['error'] == 1) {
    echo '<p class="mensaje-error">Email ya está registrado</p>';
}

// Mostrar errores de validación de contraseña u otros
if (isset($_SESSION['errores_registro'])) {
    echo '<div class="mensaje-error">';
    foreach ($_SESSION['errores_registro'] as $err) {
        echo htmlspecialchars($err) . "<br>";
    }
    echo '</div>';
    unset($_SESSION['errores_registro']);
}
?>

            <form action="../controller/procesar_registro.php" method="POST">
                <div class="form-grupo">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>

                <div class="form-grupo">
                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" required>
                </div>

                <div class="form-grupo">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-grupo">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="contraseña" name="password" required>
                    <div id="password-feedback"></div>
                </div>

                <button type="submit" class="boton">Registrarme</button>
            </form>
        </div>
    </main>

    <?php include_once __DIR__ . '/templates/footer.php'; ?>
    <script src="../../public/js/validacionContraseña.js"></script>
</body>
</html>