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
    $id_reserva = $_POST['id_reserva'] ?? null;
    $nuevo_estado = $_POST['estado'] ?? null;

    if ($id_reserva && $nuevo_estado) {
        $gestionReserva = new GestionReserva($conexion);
        $resultado = $gestionReserva->procesarActualizacionEstado($id_reserva, $nuevo_estado);
        
        header('Location: ../view/gestionAdministrador.php?status=' . ($resultado ? 'success' : 'error'));
    } else {
        header('Location: ../view/gestionAdministrador.php?status=error');
    }
    exit;
}