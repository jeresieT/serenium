<?php
session_start();
require_once '../config/config.php';
require_once 'gestionTratamiento.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    header('Location: ../view/index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validar todos los campos, incluyendo la imagen
        if (empty($_POST['id_paquete']) || empty($_POST['nombre']) || 
            empty($_POST['descripcion']) || empty($_POST['precio']) || 
            empty($_POST['duracion']) || empty($_POST['imagen'])) {
            throw new Exception('Todos los campos son obligatorios');
        }

        // Validar URL de imagen
        if (!filter_var($_POST['imagen'], FILTER_VALIDATE_URL)) {
            throw new Exception('La URL de la imagen no es válida');
        }

        $datos = [
            'nombre' => htmlspecialchars($_POST['nombre']),
            'descripcion' => htmlspecialchars($_POST['descripcion']),
            'precio' => filter_var($_POST['precio'], FILTER_VALIDATE_FLOAT),
            'duracion' => htmlspecialchars($_POST['duracion']),
            'imagen' => $_POST['imagen'] // Añadir la imagen a los datos
        ];

        $gestionReserva = new GestionReserva($conexion);
        $resultado = $gestionReserva->actualizarPaquete($_POST['id_paquete'], $datos);

        if ($resultado) {
            $_SESSION['mensaje'] = "Paquete actualizado correctamente";
            header('Location: ../view/gestionAdministrador.php?status=success&message=paquete_actualizado');
        } else {
            throw new Exception('Error al actualizar el paquete');
        }

    } catch (Exception $e) {
        error_log("Error en actualización de paquete: " . $e->getMessage());
        $_SESSION['error'] = $e->getMessage();
        header('Location: ../view/gestionAdministrador.php?status=error&message=' . urlencode($e->getMessage()));
    }
    exit;
}