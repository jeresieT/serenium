<?php
session_start();
require_once '../config/config.php';
require_once '../controller/gestionTratamiento.php';

// Verificar si el usuario es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    
    header('Location: index.php');
    exit;
}

// Inicializar el controlador
$gestionReserva = new GestionReserva($conexion);
$reservas = $gestionReserva->obtenerReservas();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Reservas - SERENIUM</title>
    <link rel="stylesheet" href="../../public/style/index.css"/>
    <link
      href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="../../public/style/gestionAdministrador.css"/>
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
            <li><a href="login.php">Cuenta</a></li>
            <?php if (isset($_SESSION['usuario_id']) && $_SESSION['usuario_rol'] == 'admin'): ?>
            <li><a href="gestionAdministrador.php" class="activo">Gestion</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<main>
    <div class="contenedor-admin">
        <div class="admin-secciones">
            <!-- Sección de Reservas -->
            <section class="seccion-reservas">
                <h2>Gestión de Reservas</h2>
                
                <?php if (isset($_GET['status'])): ?>
                    <?php if ($_GET['status'] === 'success'): ?>
                        <div class="mensaje-exito">Estado actualizado correctamente</div>
                    <?php else: ?>
                        <div class="mensaje-error">Error al actualizar el estado</div>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if ($reservas && count($reservas) > 0): ?>
                    <table class="tabla-reservas">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Paquete</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Editar estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservas as $reserva): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($reserva['id_reserva']); ?></td>
                                    <td><?php echo htmlspecialchars($reserva['nombre'] . ' ' . $reserva['apellido']); ?></td>
                                    <td><?php echo htmlspecialchars($reserva['nombre_paquete']); ?></td>
                                    <td><?php echo htmlspecialchars($reserva['fecha_reserva']); ?></td>
                                    <td class="estado-<?php echo strtolower($reserva['estado']); ?>">
                                        <?php echo htmlspecialchars($reserva['estado']); ?>
                                    </td>
                                    <td>
                                        <div class="acciones-reserva">
                                            <form action="../controller/procesar_actualizacion_estado.php" method="POST" class="form-estado">
                                                <input type="hidden" name="id_reserva" value="<?php echo $reserva['id_reserva']; ?>">
                                                <select name="estado" onchange="this.form.submit()">
                                                    <option value="pendiente" <?php echo $reserva['estado'] == 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                                                    <option value="confirmada" <?php echo $reserva['estado'] == 'confirmada' ? 'selected' : ''; ?>>Confirmada</option>
                                                    <option value="cancelada" <?php echo $reserva['estado'] == 'cancelada' ? 'selected' : ''; ?>>Cancelada</option>
                                                </select>
                                            </form>
                                            <form action="../controller/eliminar_reserva.php" method="POST" class="form-eliminar" 
                                                  onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta reserva?');">
                                                <input type="hidden" name="id_reserva" value="<?php echo $reserva['id_reserva']; ?>">
                                                <button type="submit" class="boton-eliminar">
                                                    <i class="fas fa-trash"></i> Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No hay reservas disponibles</p>
                <?php endif; ?>
            </section>

            <!-- Nueva Sección de Paquetes -->
            <section class="seccion-paquetes">
                <h2>Gestión de Paquetes</h2>
                <button class="boton-crear">Crear Nuevo Paquete</button>

                <?php 
                $paquetes = $gestionReserva->obtenerPaquetes();
                if (!empty($paquetes)): 
                ?>
                    <div class="lista-paquetes">
                        <table class="tabla-paquetes">
                            <thead>
                                <tr>
                                    <th>Imagen</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Precio</th>
                                    <th>Duración</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($paquetes as $paquete): ?>
                                    <tr>
                                        <td>
                                            <img src="<?php echo htmlspecialchars($paquete['imagen']); ?>" 
                                                 alt="<?php echo htmlspecialchars($paquete['nombre']); ?>" 
                                                 class="imagen-miniatura">
                                        </td>
                                        <td><?php echo htmlspecialchars($paquete['nombre']); ?></td>
                                        <td><?php echo htmlspecialchars($paquete['descripcion']); ?></td>
                                        <td><?php echo number_format($paquete['precio'], 2); ?>€</td>
                                        <td><?php echo htmlspecialchars($paquete['duracion']); ?></td>
                                        <td class="acciones">
                                            <button type="button" class="boton-editar" 
                                                    onclick="editarPaquete(<?php echo htmlspecialchars(json_encode($paquete)); ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="../controller/eliminar_paquete.php" method="POST" 
                                                  class="form-eliminar" style="display: inline;"
                                                  onsubmit="return confirm('¿Estás seguro de eliminar este paquete?');">
                                                <input type="hidden" name="id_paquete" 
                                                       value="<?php echo $paquete['id_paquete']; ?>">
                                                <button type="submit" class="boton-eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="sin-paquetes">No hay paquetes disponibles</p>
                <?php endif; ?>

                <div id="modal-paquete" class="modal">
                    <div class="modal-contenido">
                        <span class="cerrar">&times;</span>
                        <h3>Crear Nuevo Paquete</h3>
                        
                        <form id="form-paquete" action="../controller/procesar_paquete.php" method="POST">
                            <div class="form-grupo">
                                <label for="nombre">Nombre del Paquete:</label>
                                <input type="text" id="nombre" name="nombre" required>
                            </div>

                            <div class="form-grupo">
                                <label for="descripcion">Descripción:</label>
                                <textarea id="descripcion" name="descripcion" required></textarea>
                            </div>

                            <div class="form-grupo">
                                <label for="precio">Precio (€):</label>
                                <input type="number" id="precio" name="precio" step="0.01" min="0" required>
                            </div>

                            <div class="form-grupo">
                                <label for="duracion">Duración:</label>
                                <input type="text" id="duracion" name="duracion" placeholder="Ej: 60 minutos" required>
                            </div>

                            <div class="form-grupo">
                                <label for="imagen">URL de la Imagen:</label>
                                <input type="url" id="imagen" name="imagen" 
                                       placeholder="https://ejemplo.com/imagen.jpg" 
                                       pattern="https?://.+"
                                       title="Ingresa una URL válida que comience con http:// o https://" 
                                       required>
                                <small class="helper-text">Ingresa la URL de la imagen (debe comenzar con http:// o https://)</small>
                            </div>

                            <button type="submit" class="boton">Crear Paquete</button>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
</main>


<script src="../../public/js/gestionPaquetes.js"></script>
</body>
</html>


