<?php
require_once __DIR__ . '/../model/gestionTratamientos.php';

class GestionReserva {
    private $reservasModel;
    
    public function __construct($conexion) {
        $this->reservasModel = new Reservas($conexion);
    }
    
    public function obtenerReservas() {
        return $this->reservasModel->obtenerReservas();
    }
    
    public function obtenerReservasUsuario($id_usuario) {
        return $this->reservasModel->obtenerReservasUsuario($id_usuario);
    }
    
    public function procesarActualizacionEstado($id_reserva, $nuevo_estado) {
        if (!in_array($nuevo_estado, ['pendiente', 'confirmada', 'cancelada'])) {
            return false;
        }
        
        try {
            $resultado = $this->actualizarEstado($id_reserva, $nuevo_estado);
            if ($resultado) {
                $_SESSION['mensaje'] = "Estado actualizado correctamente";
                return true;
            } else {
                $_SESSION['mensaje'] = "Error al actualizar el estado";
                return false;
            }
        } catch (Exception $e) {
            error_log("Error al actualizar estado: " . $e->getMessage());
            $_SESSION['mensaje'] = "Error al actualizar el estado";
            return false;
        }
    }
    
    public function actualizarEstado($id_reserva, $nuevo_estado) {
        return $this->reservasModel->actualizarEstadoReserva($id_reserva, $nuevo_estado);
    }
    
    public function crearPaquete($datos) {
        return $this->reservasModel->crearPaquete($datos);
    }
    
    public function eliminarReserva($id_reserva) {
        try {
            $resultado = $this->reservasModel->eliminarReserva($id_reserva);
            if ($resultado) {
                $_SESSION['mensaje'] = "Reserva eliminada correctamente";
                return true;
            } else {
                $_SESSION['mensaje'] = "Error al eliminar la reserva";
                return false;
            }
        } catch (Exception $e) {
            error_log("Error al eliminar reserva: " . $e->getMessage());
            $_SESSION['mensaje'] = "Error al eliminar la reserva";
            return false;
        }
    }

    public function obtenerPaquetes() {
        try {
            return $this->reservasModel->obtenerPaquetes();
        } catch (Exception $e) {
            error_log("Error al obtener paquetes: " . $e->getMessage());
            return [];
        }
    }

    public function eliminarPaquete($id_paquete) {
        try {
            $resultado = $this->reservasModel->eliminarPaquete($id_paquete);
            if ($resultado) {
                $_SESSION['mensaje'] = "Paquete eliminado correctamente";
                return true;
            } else {
                $_SESSION['mensaje'] = "Error al eliminar el paquete";
                return false;
            }
        } catch (Exception $e) {
            error_log("Error al eliminar paquete: " . $e->getMessage());
            $_SESSION['mensaje'] = "Error al eliminar el paquete";
            return false;
        }
    }

    public function actualizarPaquete($id_paquete, $datos) {
        try {
            return $this->reservasModel->actualizarPaquete($id_paquete, $datos);
        } catch (Exception $e) {
            error_log("Error al actualizar paquete: " . $e->getMessage());
            throw $e;
        }
    }
}