<?php
require_once '../config/config.php';

header('Content-Type: application/json');

if (isset($_GET['fecha'])) {
    try {
        $stmt = $conexion->prepare("
            SELECT COUNT(*) as total 
            FROM Reservas 
            WHERE fecha_reserva = ?
        ");
        $stmt->execute([$_GET['fecha']]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'status' => 'success',
            'reservas' => (int)$resultado['total']
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al verificar disponibilidad'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Fecha no proporcionada'
    ]);
}