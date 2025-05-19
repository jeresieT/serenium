<?php
session_start();
require_once '../config/config.php';
require_once 'gestionTratamiento.php';
require_once '../model/gestionTratamientos.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    header('Location: ../view/index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //sino se recibe el id_paquete, redirigir a la página de gestión
    //sino existe el paquete se pone nulo
    $id_paquete = $_POST['id_paquete'] ?? null;

    if ($id_paquete) {
        $gestionReserva = new GestionReserva($conexion);
        $resultado = $gestionReserva->eliminarPaquete($id_paquete);
        
        header('Location: ../view/gestionAdministrador.php?status=' . 
               ($resultado ? 'success' : 'error') . 
               '&message=' . ($resultado ? 'paquete_eliminado' : 'error_eliminar_paquete'));
    } else {
        header('Location: ../view/gestionAdministrador.php?status=error&message=invalid_id');
    }
    exit;
}