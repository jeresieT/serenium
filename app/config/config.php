<?php

//lo pongo en una constante y lo guardo en "variables"
//nombre de la constannte -> valor de la constante
define('DB_HOST', 'bbdd.aronacoffes.cat');
define('DB_USER', 'ddb253701');
define('DB_PASS', 'Jeresietorres.alum@insestatut25
');
define('DB_NAME', 'ddb253701');

try {
    // Conexión a la base de datos
    // PDO para conectarme a la bases de datos
    // y ejecutar consultas de manera segura y eficiente.
    $conexion = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);

    //el settAtAttribute() para que me explique los errores de la base de datos
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    die();
}
?>

