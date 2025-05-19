<?php
session_start();
require_once '../config/config.php';

//con server compruebo si el metodo que uso es POST
// si es asi, obtengo el email y la contraseña del formulario de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {

        $stmt = $conexion->prepare("SELECT * FROM Usuarios WHERE email = :email");
        //cuando uso los dos puntos, le tengo que pasar un array asociativo
        //para que me reconozca el nombre de la variable
        $stmt->execute([':email' => $email]);

        //fetch() me devuelve una fila de resultados como un array asociativo, con el email que busco 
        //en la varibale usuario entonces guardo todos los datos de ese email
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario['contraseña'])) {
        
            // Login exitoso
            
            //La session guarda informacion del usuario y se puede ver desde cualquier parte de la web y la mantiene hasta que cierre sesion o el navegador

            //nombre de la variable de session -> valor de la variable de session
            //el usuario es de la bd
            $_SESSION['usuario_id'] = $usuario['id_usuario'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_rol'] = $usuario['rol'];
            
            // Redirigir según el origen
            if (isset($_SESSION['redirect_after_login'])) {
                $redirect = $_SESSION['redirect_after_login'];
                unset($_SESSION['redirect_after_login']);
                header("Location: " . $redirect);
            } else {
                header("Location: ../view/index.php");
            }
            exit;
        } else {
            // Login fallido
            header("Location: ../view/login.php?error=1");
            exit;
        }
    } catch(PDOException $e) {
        error_log("Error en login: " . $e->getMessage());
        header("Location: ../view/login.php?error=1");
        exit;
    }
}
?>