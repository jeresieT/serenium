<?php
session_start();
require_once '../config/config.php';
require_once 'gestionTratamiento.php';

// Verificar si es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    header('Location: ../view/index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validar datos recibidos
        if (empty($_POST['nombre']) || empty($_POST['descripcion']) || 
            empty($_POST['precio']) || empty($_POST['duracion']) || empty($_POST['imagen'])) {
            throw new Exception('Todos los campos son obligatorios');
        }

        // Validar URL
        if (!filter_var($_POST['imagen'], FILTER_VALIDATE_URL)) {
            throw new Exception('La URL de la imagen no es válida');
        }

        // Preparar datos del paquete
        $datos = [
            'nombre' => htmlspecialchars($_POST['nombre']),
            'descripcion' => htmlspecialchars($_POST['descripcion']),
            'precio' => filter_var($_POST['precio'], FILTER_VALIDATE_FLOAT),
            'duracion' => htmlspecialchars($_POST['duracion']),
            'imagen' => $_POST['imagen']
        ];

        // Crear el paquete
        $gestionReserva = new GestionReserva($conexion);
        if ($gestionReserva->crearPaquete($datos)) {
            $_SESSION['mensaje'] = "Paquete creado correctamente";
            header('Location: ../view/gestionAdministrador.php?status=success&message=paquete_creado');
        } else {
            throw new Exception('Error al crear el paquete');
        }

    } catch (Exception $e) {
        error_log("Error en creación de paquete: " . $e->getMessage());
        $_SESSION['error'] = $e->getMessage();
        header('Location: ../view/gestionAdministrador.php?status=error&message=' . urlencode($e->getMessage()));
    }
    exit;
}