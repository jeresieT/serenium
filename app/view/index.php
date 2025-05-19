<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SERENIUM</title>
    <link rel="stylesheet" href="../../public/style/index.css"/>
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
          <li><a href="login.php">Cuenta</a></li>
          <?php if (isset($_SESSION['usuario_id']) && $_SESSION['usuario_rol'] == 'admin'): ?>
          <li><a href="gestionAdministrador.php">Gestion</a></li>
        <?php endif; ?>
        </ul>
      </nav>
    </header>


    <main>
      <!-- Sección de Bienvenida -->
      <section class="bienvenida">
        <h1>Bienvenido a Serenium</h1>
        <p>
          Descubre un oasis de relajación y bienestar donde el estrés desaparece
          y la calma te envuelve.
        </p>
        <a href="#paquetes" class="btn">Explora Nuestros Paquetes</a>
      </section>

      <!-- section para mas contenido -->
      <section class="sobre-nosotros">
        <h2>¿Quiénes Somos?</h2>
        <img src="../../public/img/spa.jpg" alt="Spa Serenium">
        <p>
          En SERENIUM Spa, somos un equipo apasionado por el bienestar. Nuestros
          terapeutas expertos combinan técnicas tradicionales y modernas para
          ofrecerte una experiencia única que renueva cuerpo y mente. Nos
          dedicamos a crear un espacio donde te sientas cuidada y en paz.
        </p>
      </section>

      <!-- Nueva sección de contenido -->
      <section class="seccion-info">
        <div class="contenido-info">
          <h2>Masajes Terapéuticos</h2>
          <p>
            Nuestros masajes terapéuticos combinan diferentes técnicas para aliviar tensiones, 
            reducir el estrés y mejorar tu bienestar general. Cada sesión está diseñada 
            para adaptarse a tus necesidades específicas.
          </p>
          <img src="../../public/img/masaje3.jpg" alt="Masajes terapéuticos">
        </div>
      </section>

      <!-- Nueva sección de contenido invertida -->
      <section class="seccion-info invertido">
        <div class="contenido-info">
          <h2>Experiencia Premium</h2>
          <p>
            Disfruta de una experiencia de spa premium en un ambiente diseñado para 
            tu máxima comodidad y relajación. Nuestras instalaciones y servicios 
            te transportarán a un oasis de tranquilidad.
          </p>
          <img src="../../public/img/masaje2.jpg" alt="Experiencia premium">
        </div>
      </section>


      <!-- Sección API - Promociones -->
      <section id="promociones" class="promociones">
          <!-- El contenido se cargará dinámicamente con JavaScript -->
      </section>

      <!-- Sección de Paquetes -->
      <section class="vista-paquetes" id="paquetes">
        <h2>Nuestros Paquetes</h2>
        <div class="rejilla-paquetes">
          <div class="paquete">
            <h3>Relajación Básica</h3>
            <p>Masaje sueco de 60 minutos con aromaterapia.</p>
            <p class="precio">50€</p>
          </div>
          <div class="paquete">
            <h3>Experiencia Profunda</h3>
            <p>Masaje de tejido profundo 90 min con piedras calientes.</p>
            <p class="precio">80€</p>
          </div>
          <div class="paquete">
            <h3>Lujo Completo</h3>
            <p>Masaje completo 120 min, exfoliación y aromaterapia premium.</p>
            <p class="precio">120€</p>
          </div>
        </div>
        <a href="paquetes.php" class="boton">Ver Todos los Paquetes</a>
      </section>
    </main>

    <?php include_once __DIR__. '/templates/footer.php'; ?>

    <script src="../../public/js/api.js"></script>

  </body>
</html>
