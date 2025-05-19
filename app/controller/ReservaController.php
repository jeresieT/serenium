<?php
require_once '../model/Usuario.php';

class ReservaController {
    private $conexion;
    private $usuarioModel;

    public function __construct($conexion) {
        $this->conexion = $conexion;
        $this->usuarioModel = new Usuario($conexion);
    }

    public function obtenerDatosFormulario($id_usuario) {
        return $this->usuarioModel->obtenerDatosUsuario($id_usuario);
    }
}