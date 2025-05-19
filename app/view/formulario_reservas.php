<?php
session_start();
require_once '../config/config.php';
require_once '../controller/ReservaController.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php?required=1");
    exit;
}

// Inicializar controlador y obtener datos
$reservaController = new ReservaController($conexion);
$datosUsuario = $reservaController->obtenerDatosFormulario($_SESSION['usuario_id']);

// Obtener datos del paquete seleccionado
$paquete = $_GET['paquete'] ?? '';
$precio = $_GET['precio'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SERENIUM</title>
    <link rel="stylesheet" href="../../public/style/index.css"/>
    <link rel="stylesheet" href="../../public/style/formularioReserva.css"/>
    <link
      href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400&display=swap"
      rel="stylesheet"/>

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
          <li><a href="index.php" class="activo">Inicio</a></li>
          <li><a href="paquetes.php">Paquetes</a></li>
          <li><a href="contacto.php">Contacto</a></li>
          
        </ul>
      </nav>
    </header>

<body>

    <main>
        <div class="formulario-reserva">
            <h2>Formulario de Reserva</h2>

            <div class="detalles-paquete">
                <h3>Detalles del Paquete Seleccionado</h3>
                <p><strong>Paquete:</strong> <?php echo htmlspecialchars($paquete); ?></p>
                <p><strong>Precio:</strong> <?php echo htmlspecialchars($precio); ?></p>
            </div>

            <form action="../controller/procesar_reserva.php" method="POST" id="formReserva">
                <input type="hidden" name="paquete" value="<?php echo htmlspecialchars($paquete); ?>">
                <input type="hidden" name="precio" value="<?php echo htmlspecialchars($precio); ?>">

                <div class="form-grupo">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" 
                           value="<?php echo htmlspecialchars($datosUsuario['nombre'] ?? ''); ?>" required>
                </div>

                <div class="form-grupo">
                    <label for="apellidos">Apellidos:</label>
                    <input type="text" id="apellidos" name="apellidos" 
                           value="<?php echo htmlspecialchars($datosUsuario['apellido'] ?? ''); ?>" required>
                </div>

                <div class="form-grupo">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" 
                           value="<?php echo htmlspecialchars($datosUsuario['email'] ?? ''); ?>" required>
                </div>

                <div class="form-grupo">
                    <label for="telefono">Teléfono:</label>
                    <input type="tel" id="telefono" name="telefono" 
                           value="<?php echo htmlspecialchars($datosUsuario['telefono'] ?? ''); ?>" required>
                </div>


                <?php
    // Fecha actual
    $min = date('Y-m-d');

    // Fecha máxima (30 días a partir de hoy)
    $max = date('Y-m-d', strtotime('+30 days'));
?>
                <div class="form-grupo">
                    <label for="fecha">Fecha deseada:</label>
                    <input type="date" id="fecha" name="fecha"  required min="<?php echo $min; ?>" max="<?php echo $max; ?>">
                </div>

                <button type="submit" class="boton" id="btnReserva">Confirmar Reserva</button>
            </form>
        </div>
    </main>

    <?php include_once __DIR__ . '/templates/footer.php'; ?>
    
    <script src="../../public/js/formularioReserva.js"></script>

</body>
</html>

