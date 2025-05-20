<?php
session_start();
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validación de requisitos de contraseña
    $errores = [];
    if (strlen($password) < 8) {
        $errores[] = "La contraseña debe tener al menos 8 caracteres.";
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $errores[] = "Debe contener al menos una letra mayúscula.";
    } elseif (!preg_match('/[a-z]/', $password)) {
        $errores[] = "Debe contener al menos una letra minúscula.";
    } elseif (!preg_match('/[0-9]/', $password)) {
        $errores[] = "Debe contener al menos un número.";
    } elseif (!preg_match('/[^A-Za-z0-9]/', $password)) {
        $errores[] = "Debe contener al menos un caracter especial.";
    }

    if (!empty($errores)) {
        $_SESSION['errores_registro'] = $errores;
        header("Location: ../view/formulario_registro.php?error=3");
        exit;
    }

    try {
        // Añadir debug
        error_log("Intentando registrar usuario: " . $email);

        // Verificar si el email ya está registrado
        $stmt = $conexion->prepare("SELECT * FROM Usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuarioExistente = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuarioExistente) {
            header("Location: ../view/formulario_registro.php?error=1");
            exit;
        }

        // Insertar nuevo usuario
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conexion->prepare("INSERT INTO Usuarios (nombre, apellido, email, contraseña, rol) VALUES (?, ?, ?, ?, 'cliente')");
        
        $resultado = $stmt->execute([
            $nombre,
            $apellido,
            $email,
            $hashedPassword
        ]);

        if ($resultado) {
            // Debug exitoso
            error_log("Usuario registrado correctamente: " . $email);
            header("Location: ../view/login.php?registro_exitoso=1");
        } else {
            // Debug error
            error_log("Error al registrar usuario: " . print_r($stmt->errorInfo(), true));
            header("Location: ../view/formulario_registro.php?error=2");
        }
        exit;
        
    } catch(PDOException $e) {
        // Log detallado del error
        error_log("Error en registro: " . $e->getMessage());
        error_log("Stack trace: " . $e->getTraceAsString());
        header("Location: ../view/formulario_registro.php?error=2");
        exit;
    }
}
?>