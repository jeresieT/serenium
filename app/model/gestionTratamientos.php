<?php

class Reservas {
    //ATRIBUTO de la clase que en este caso va tener la variabale de conexion 
    private $conexion;

    // Constructor que recibe la conexión a la base de datos
    // y la almacena en una propiedad de la clase
    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function obtenerReservas() {
        $sql = "SELECT r.*, u.nombre, u.apellido, p.nombre as nombre_paquete 
                FROM Reservas r 
                INNER JOIN Usuarios u ON r.id_usuario = u.id_usuario 
                INNER JOIN Paquetes p ON r.id_paquete = p.id_paquete
                ORDER BY r.fecha_reserva DESC";

        //llamnando a la variable de conexion y ejecuto la consulta 
        $resultado = $this->conexion->query($sql);

        //fetchAll() para obtener todos los resultados de la consulta
        //PDO::FETCH_ASSOC para obtener los resultados como un array asociativo
        //es decir, con los nombres de las columnas como claves
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerReservasUsuario($id_usuario) {
        $sql = "SELECT r.*, p.nombre as nombre_paquete, p.precio, r.fecha_reserva, r.estado 
                FROM Reservas r 
                INNER JOIN Paquetes p ON r.id_paquete = p.id_paquete 
                WHERE r.id_usuario = ? AND r.fecha_reserva >= CURDATE()
                ORDER BY r.fecha_reserva ASC";
                
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$id_usuario]);
        $reservasFuturas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Obtener reservas pasadas
        $sql = "SELECT r.*, p.nombre as nombre_paquete, p.precio, r.fecha_reserva, r.estado 
                FROM Reservas r 
                INNER JOIN Paquetes p ON r.id_paquete = p.id_paquete 
                WHERE r.id_usuario = ? AND r.fecha_reserva < CURDATE()
                ORDER BY r.fecha_reserva DESC";
                
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$id_usuario]);
        $reservasPasadas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Combinar reservas futuras y pasadas
        return array_merge($reservasFuturas, $reservasPasadas);
    }

    public function actualizarEstadoReserva($id_reserva, $nuevo_estado) {
        $sql = "UPDATE Reservas SET estado = ? WHERE id_reserva = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$nuevo_estado, $id_reserva]);
    }

    public function crearPaquete($datos) {
        try {
            // Validar que la imagen sea una URL válida
            if (!filter_var($datos['imagen'], FILTER_VALIDATE_URL)) {
                throw new Exception("La URL de la imagen no es válida");
            }

            $sql = "INSERT INTO Paquetes (nombre, descripcion, precio, duracion, imagen) 
                    VALUES (?, ?, ?, ?, ?)";
                    
            $stmt = $this->conexion->prepare($sql);
            
            return $stmt->execute([
                $datos['nombre'],
                $datos['descripcion'],
                $datos['precio'],
                $datos['duracion'],
                $datos['imagen']
            ]);
        } catch(PDOException $e) {
            error_log("Error al crear paquete: " . $e->getMessage());
            throw $e;
        }
    }

    public function eliminarReserva($id_reserva) {
        $sql = "DELETE FROM Reservas WHERE id_reserva = ?";
        try {
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([$id_reserva]);
        } catch(PDOException $e) {
            error_log("Error al eliminar reserva: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerPaquetes() {
        $sql = "SELECT * FROM Paquetes ORDER BY id_paquete DESC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminarPaquete($id_paquete) {
        try {
            // Ya no necesitamos manejar archivos físicos, solo eliminamos el registro
            $sql = "DELETE FROM Paquetes WHERE id_paquete = ?";
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([$id_paquete]);
        } catch(PDOException $e) {
            error_log("Error al eliminar paquete: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarPaquete($id_paquete, $datos) {
        try {
            $sql = "UPDATE Paquetes SET 
                    nombre = ?, 
                    descripcion = ?, 
                    precio = ?, 
                    duracion = ?, 
                    imagen = ? 
                    WHERE id_paquete = ?";

            $params = [
                $datos['nombre'],
                $datos['descripcion'],
                $datos['precio'],
                $datos['duracion'],
                $datos['imagen'],
                $id_paquete
            ];
            
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute($params);
        } catch(PDOException $e) {
            error_log("Error al actualizar paquete: " . $e->getMessage());
            throw $e;
        }
    }
}