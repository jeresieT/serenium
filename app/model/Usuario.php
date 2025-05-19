<?php
class Usuario {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function obtenerDatosUsuario($id_usuario) {
        try {
            $stmt = $this->conexion->prepare("SELECT * FROM Usuarios WHERE id_usuario = ?");
            $stmt->execute([$id_usuario]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error al obtener datos del usuario: " . $e->getMessage());
            return false;
        }
    }
}