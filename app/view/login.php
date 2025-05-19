<?php

session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once '../config/config.php';  // Esto nos da acceso a $conexion
require_once '../controller/gestionTratamiento.php';
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
      rel="stylesheet" />
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
        <li><a href="index.php">Inicio</a></li>
        <li><a href="paquetes.php">Paquetes</a></li>
        <li><a href="contacto.php">Contacto</a></li>
        <li><a href="login.php" class="activo">Cuenta</a></li>
        <?php if (isset($_SESSION['usuario_id']) && $_SESSION['usuario_rol'] == 'admin'): ?>
          <li><a href="gestionAdministrador.php">Gestion</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </header>

  <main>
    <div class="formulario-login">
      <?php
      if (isset($_GET['error'])) {
        echo '<p class="mensaje-error">Email o contraseña incorrectos</p>';
      }
      if (isset($_GET['required'])) {
        echo '<p class="mensaje-error">Debes iniciar sesión para hacer una reserva</p>';
      }
      ?>
      <?php if (!isset($_SESSION['usuario_id'])): ?>
        <h2>Iniciar Sesión</h2>
      <form action="../controller/procesar_login.php" method="POST">
        <div class="form-grupo">
          <label for="email">Email:</label>
          <input type="email" id="email" name="email" required>
        </div>

        <div class="form-grupo">
          <label for="password">Contraseña:</label>
          <input type="password" id="password" name="password" required>
        </div>

        <button type="submit" class="boton">Iniciar Sesión</button>
      </form>

      <p class="mensaje">¿No tienes cuenta? <a href="formulario_registro.php">Regístrate aquí</a></p>

      <?php else: ?>
      <div class="usuario-logueado">
        <h2>Mi Cuenta</h2>
        <p class="bienvenida">Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></p>
        
        <?php
        $gestionReserva = new GestionReserva($conexion);
        $misReservas = $gestionReserva->obtenerReservasUsuario($_SESSION['usuario_id']);
        ?>

        <div class="mis-reservas">
            <h3>Mis Tratamientos Reservados</h3>
            <?php if (!empty($misReservas)): ?>
                <table class="tabla-reservas">
                    <thead>
                        <tr>
                            <th>Tratamiento</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Precio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($misReservas as $reserva): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($reserva['nombre_paquete']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($reserva['fecha_reserva'])); ?></td>
                                <td class="estado-<?php echo strtolower($reserva['estado']); ?>">
                                    <?php echo htmlspecialchars($reserva['estado']); ?>
                                </td>
                                <td><?php echo htmlspecialchars($reserva['precio']); ?>€</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="sin-reservas">No tienes tratamientos reservados</p>
                <a href="paquetes.php" class="boton-secundario">Ver tratamientos disponibles</a>
            <?php endif; ?>
        </div>

        <form action="../controller/logout.php" method="POST" class="form-logout">
            <button type="submit" class="boton">Cerrar sesión</button>
        </form>
      </div>
      <?php endif; ?>

      
    </div>
  </main>

  <?php include_once __DIR__ . '/templates/footer.php'; ?>
</body>

</html>