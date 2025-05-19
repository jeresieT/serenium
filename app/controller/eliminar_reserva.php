<?php
session_start();
require_once '../config/config.php';
require_once 'gestionTratamiento.php';

// Verificar si es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    header('Location: ../view/index.php');
    exit;
}
// Eliminar reserva
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_reserva = $_POST['id_reserva'] ?? null;

    if ($id_reserva) {
        $gestionReserva = new GestionReserva($conexion);
        $resultado = $gestionReserva->eliminarReserva($id_reserva);
        
        header('Location: ../view/gestionAdministrador.php?status=' . ($resultado ? 'success' : 'error') . 
               '&message=' . ($resultado ? 'deleted' : 'error_delete'));
    } else {
        header('Location: ../view/gestionAdministrador.php?status=error&message=invalid_id');
    }
    exit;
}