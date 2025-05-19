<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Paquetes - SERENIUM</title>
    <link rel="stylesheet" href="../../public/style/index.css"/>
    <link rel="stylesheet" href="../../public/style/paquetes.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400&display=swap" rel="stylesheet"/>

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
                <li><a href="paquetes.php" class="activo">Paquetes</a></li>
                <li><a href="contacto.php">Contacto</a></li>
                <li><a href="login.php">Cuenta</a></li>
                <?php if (isset($_SESSION['usuario_id']) && $_SESSION['usuario_rol'] == 'admin'): ?>
          <li><a href="gestionAdministrador.php">Gestion</a></li>
        <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>
      <section class="paquetes">
        <h1>Nuestros Paquetes de Masajes</h1>
        <div class="rejilla-paquetes">
          <?php
          require_once __DIR__ . '/../config/config.php';
          
          try {
              $stmt = $conexion->query("SELECT * FROM Paquetes");
              $paquetes = $stmt->fetchAll(PDO::FETCH_ASSOC);
              
              //Para crear un paquete de masajes, para recorrer cada paquete y mostrar su información.
              // htmlspecialchars para evitar inyecciones de código HTML o JavaScript en los datos mostrados.
              foreach($paquetes as $paquete) {
                  $id = $paquete['id_paquete'];
                  $nombre = htmlspecialchars($paquete['nombre']);
                  $descripcion = htmlspecialchars($paquete['descripcion']);
                  $precio = htmlspecialchars($paquete['precio']);
                  $duracion = htmlspecialchars($paquete['duracion']);
                  $imagen = htmlspecialchars($paquete['imagen']);
          ?>
                  <div id="paquete<?= $id ?>" class="paquete">
                      <h2><?= $nombre ?></h2>
                      <img src="<?= $imagen ?>" alt="<?= $nombre ?>">
                      <div id="info<?= $id ?>" class="informacion">
                          <p><?= $descripcion ?></p>
                          <p>Duración: <?= $duracion ?></p>
                          <p class="precio"><?= $precio ?>€</p>
                          <a href="formulario_reservas.php?paquete=<?= urlencode($nombre) ?>&precio=<?= urlencode($precio) ?>" class="boton">Reservar</a>
                      </div>
                  </div>
          <?php
              }
          } catch(PDOException $e) {
              echo "Error: " . $e->getMessage();
          }
          ?>
        </div>
      </section>
    </main>

    <?php include_once __DIR__. '/templates/footer.php'; ?>
  </body>
</html>
