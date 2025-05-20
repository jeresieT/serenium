<?php
session_start(); // Añadir esto al inicio
require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../view/login.php?required=1');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../config/config.php';
    
    // Obtener datos del formulario con verificación
    $fecha = $_POST['fecha'] ?? '';
    $id_usuario = $_SESSION['usuario_id'] ?? null;
    $paquete = $_POST['paquete'] ?? '';
    $telefono = $_POST['telefono'];

    // Validar teléfono
    if (!preg_match('/^[0-9]{9}$/', $telefono)) {
        $_SESSION['error'] = "El número de teléfono debe tener 9 dígitos";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Validar que tengamos todos los datos necesarios
    if (!$fecha || !$id_usuario || !$paquete) {
        header("Location: ../view/confirmacion.php?status=error&message=missing_data");
        exit;
    }

    try {
        // Verificar si ya existe una reserva
        $stmtVerificar = $conexion->prepare("
            SELECT COUNT(*) FROM Reservas r
            INNER JOIN Paquetes p ON r.id_paquete = p.id_paquete
            WHERE r.id_usuario = ? AND r.fecha_reserva = ? AND p.nombre = ?
        ");
        $stmtVerificar->execute([$id_usuario, $fecha, $paquete]);
        $reservaExistente = $stmtVerificar->fetchColumn();

        if ($reservaExistente > 0) {
            header("Location: ../view/confirmacion.php?status=error&message=duplicate");
            exit;
        }

        // Verificar el número de reservas para esa fecha
        $stmtContarReservas = $conexion->prepare("
            SELECT COUNT(*) FROM Reservas 
            WHERE fecha_reserva = ?
        ");
        $stmtContarReservas->execute([$fecha]);
        $numeroReservas = $stmtContarReservas->fetchColumn();

        if ($numeroReservas >= 8) {
            header("Location: ../view/confirmacion.php?status=error&message=full");
            exit;
        }

        // Obtener el ID del paquete
        $stmtPaquete = $conexion->prepare("SELECT id_paquete FROM Paquetes WHERE nombre = ?");
        $stmtPaquete->execute([$paquete]);
        $paqueteInfo = $stmtPaquete->fetch(PDO::FETCH_ASSOC);
        
        if (!$paqueteInfo) {
            throw new Exception("Paquete no encontrado");
        }

        // Insertar la reserva
        $stmt = $conexion->prepare("
            INSERT INTO Reservas (id_usuario, id_paquete, fecha_reserva, estado) 
            VALUES (?, ?, ?, 'pendiente')
        ");
        $stmt->execute([$id_usuario, $paqueteInfo['id_paquete'], $fecha]);

        // Crear una nueva instancia de PHPMailer
        $mail = new PHPMailer(true);

        // Configuración del servidor
        $mail->isSMTP();                                       
        $mail->Host       = 'smtp.dondominio.com';              // Servidor SMTP de Dondominio
        $mail->SMTPAuth   = true;                              
        $mail->Username   = 'info@aronacoffes.cat';              // Tu email de Dondominio
        $mail->Password   = 'Estatut$25';        // Tu contraseña de Dondominio
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;    
        $mail->Port       = 587;                               
        $mail->CharSet    = 'UTF-8';                           

        // Remitente (usando la cuenta de Dondominio)
        $mail->setFrom('info@aronacoffes.cat', 'SERENIUM');
        $mail->addReplyTo('info@aronacoffes.cat', 'SERENIUM');

        // Destinatario (el correo institucional del usuario)
        $mail->addAddress($_POST['email'], $_POST['nombre'] . ' ' . $_POST['apellidos']);

        // Contenido
        $mail->isHTML(true);
        $mail->Subject = 'Confirmación de reserva - SERENIUM';
        
        // Cuerpo del email en HTML
        $mail->Body = "
        <html>
        <head>
            <style>
                /* Usando las mismas variables de color que index.css */
                :root {
                    --color-primario: #3c1810;    
                    --color-secundario: #a67c52;  
                    --color-acento: #d4b08c;     
                    --texto-claro: #ffffff;       
                    --texto-oscuro: #2d1309;        
                    --fondo: #f5f0eb;
                }
                
                body {
                    font-family: 'Poppins', Arial, sans-serif;
                    line-height: 1.6;
                    color: #2d1309;
                    background-color: #f5f0eb;
                    margin: 0;
                    padding: 0;
                    font-size: 18px; /* Aumentado de 16px */
                }
                
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 40px 20px;
                    background-color: #ffffff;
                    border-radius: 15px;
                    box-shadow: 0 5px 20px rgba(60, 24, 16, 0.1);
                }
                
                .header {
                    text-align: center;
                    margin-bottom: 30px;
                }
                
                .header h2 {
                    font-family: 'Playfair Display', serif;
                    font-size: 36px; /* Aumentado de 28px */
                    color: #3c1810;
                    margin-bottom: 15px;
                }
                
                .header p {
                    font-size: 22px; /* Añadido */
                }
                
                .details {
                    background-color: #f5f0eb;
                    padding: 20px;
                    border-radius: 10px;
                    margin: 20px 0;
                }
                
                .details h3 {
                    font-family: 'Playfair Display', serif;
                    color: #3c1810;
                    font-size: 28px; /* Aumentado de 20px */
                    margin-bottom: 15px;
                }
                
                .details ul {
                    list-style: none;
                    padding: 0;
                }
                
                .details li {
                    padding: 10px 0;
                    border-bottom: 1px solid #d4b08c;
                    color: #2d1309;
                    font-size: 20px; /* Aumentado */
                }
                
                .details li:last-child {
                    border-bottom: none;
                }
                
                .details strong {
                    color: #a67c52;
                }
                
                .message {
                    text-align: center;
                    padding: 20px 0;
                    font-size: 22px; /* Aumentado de 16px */
                    color: #3c1810;
                }
                
                .footer {
                    text-align: center;
                    margin-top: 30px;
                    padding-top: 20px;
                    border-top: 2px solid #f5f0eb;
                    color: #a67c52;
                    font-size: 20px; /* Aumentado de 14px */
                }
                
                .logo {
                    text-align: center;
                    margin-bottom: 20px;
                }
                
                .logo h1 {
                    font-family: 'Playfair Display', serif;
                    color: #3c1810;
                    font-size: 42px; /* Aumentado de 32px */
                    margin: 0;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='logo'>
                    <h1>SERENIUM</h1>
                </div>
                
                <div class='header'>
                    <h2>Estimado/a {$_POST['nombre']} {$_POST['apellidos']}</h2>
                    <p>¡Gracias por reservar con SERENIUM!</p>
                </div>
                
                <div class='details'>
                    <h3>Detalles de su reserva</h3>
                    <ul>
                        <li><strong>Paquete:</strong> {$paquete}</li>
                        <li><strong>Precio:</strong> {$_POST['precio']}</li>
                        <li><strong>Fecha reservada:</strong> {$fecha}</li>
                        <li><strong>Teléfono de contacto:</strong> {$_POST['telefono']}</li>
                    </ul>
                </div>
                
                <div class='message'>
                    <p>Nos pondremos en contacto con usted pronto para confirmar todos los detalles.</p>
                </div>
                
                <div class='footer'>
                    <p>Saludos cordiales,</p>
                    <p><strong>Equipo SERENIUM</strong></p>
                </div>
            </div>
        </body>
        </html>";

        // Versión alternativa en texto plano
        $mail->AltBody = "
        Estimado/a {$_POST['nombre']} {$_POST['apellidos']},

        ¡Gracias por reservar con SERENIUM!

        Detalles de su reserva:
        - Paquete: {$paquete}
        - Precio: {$_POST['precio']}
        - Fecha reservada: {$fecha}
        - Teléfono de contacto: {$_POST['telefono']}

        Nos pondremos en contacto con usted pronto para confirmar todos los detalles.

        Saludos cordiales,
        Equipo SERENIUM";

        // Enviar el email
        $mail->send();

        // Nuevo correo para el administrador
        $mailAdmin = new PHPMailer(true);
        $mailAdmin->isSMTP();
        $mailAdmin->Host       = 'smtp.dondominio.com';
        $mailAdmin->SMTPAuth   = true;
        $mailAdmin->Username   = 'info@aronacoffes.cat';
        $mailAdmin->Password   = 'Estatut$25';
        $mailAdmin->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mailAdmin->Port       = 587;
        $mailAdmin->CharSet    = 'UTF-8';

        // Configuración del correo para el administrador
        $mailAdmin->setFrom('info@aronacoffes.cat', 'Sistema SERENIUM');
        $mailAdmin->addAddress('jeresietorres.alum@insestatut.cat', 'Administrador SERENIUM'); // Cambia esto por el email del administrador

        $mailAdmin->isHTML(true);
        $mailAdmin->Subject = 'Nueva Reserva - SERENIUM';

        // Cuerpo del email para el administrador - Diseño más práctico
        $mailAdmin->Body = "
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    margin: 0;
                    padding: 0;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                }
                .alert {
                    background-color: #3c1810;
                    color: white;
                    padding: 15px;
                    text-align: center;
                    font-size: 20px;
                    border-radius: 5px 5px 0 0;
                    margin-bottom: 0;
                }
                .content {
                    background-color: #fff;
                    border: 1px solid #ddd;
                    border-radius: 0 0 5px 5px;
                    padding: 20px;
                }
                .info-group {
                    background: #f8f9fa;
                    padding: 15px;
                    margin: 10px 0;
                    border-left: 4px solid #a67c52;
                }
                .info-title {
                    color: #3c1810;
                    font-weight: bold;
                    margin-bottom: 10px;
                    font-size: 16px;
                }
                .info-item {
                    margin: 5px 0;
                    padding: 5px 0;
                }
                .urgente {
                    display: inline-block;
                    background-color: #dc3545;
                    color: white;
                    padding: 3px 8px;
                    border-radius: 3px;
                    font-size: 12px;
                    margin-left: 10px;
                }
                .action-button {
                    display: inline-block;
                    background-color: #28a745;
                    color: white;
                    padding: 10px 20px;
                    text-decoration: none;
                    border-radius: 5px;
                    margin-top: 15px;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='alert'>
                    Nueva Reserva - Requiere Confirmación
                </div>
                
                <div class='content'>
                    <div class='info-group'>
                        <div class='info-title'>📋 DATOS DEL CLIENTE</div>
                        <div class='info-item'><strong>Nombre:</strong> {$_POST['nombre']} {$_POST['apellidos']}</div>
                        <div class='info-item'><strong>Email:</strong> {$_POST['email']}</div>
                        <div class='info-item'><strong>Teléfono:</strong> {$_POST['telefono']}</div>
                    </div>

                    <div class='info-group'>
                        <div class='info-title'>🎯 DETALLES DE LA RESERVA</div>
                        <div class='info-item'><strong>Paquete:</strong> {$paquete}</div>
                        <div class='info-item'><strong>Precio:</strong> {$_POST['precio']}€</div>
                        <div class='info-item'>
                            <strong>Fecha:</strong> " . date('d/m/Y', strtotime($fecha)) . "
                            " . (strtotime($fecha) - time() < 7 * 24 * 60 * 60 ? "<span class='urgente'>Próxima semana</span>" : "") . "
                        </div>
                    </div>

                    <div style='text-align: center; margin-top: 20px;'>
                        <a href='http://localhost/proyectoSerenium/app/view/gestionAdministrador.php' 
                           class='action-button'>
                            ✅ Gestionar Reserva
                        </a>
                    </div>
                </div>
            </div>
        </body>
        </html>";

        // Versión texto plano más práctica
        $mailAdmin->AltBody = "
        NUEVA RESERVA - Requiere Confirmación

        DATOS DEL CLIENTE:
        - Nombre: {$_POST['nombre']} {$_POST['apellidos']}
        - Email: {$_POST['email']}
        - Teléfono: {$_POST['telefono']}

        DETALLES DE LA RESERVA:
        - Paquete: {$paquete}
        - Precio: {$_POST['precio']}€
        - Fecha: " . date('d/m/Y', strtotime($fecha)) . 
        (strtotime($fecha) - time() < 7 * 24 * 60 * 60 ? " (PRÓXIMA SEMANA)" : "") . "

        Gestionar reserva en: http://localhost/proyectoSerenium/app/view/gestionAdministrador.php";

        // Enviar el email al administrador
        $mailAdmin->send();

        // Redirigir a página de confirmación
        header("Location: ../view/confirmacion.php?status=success");
        exit;
        
    } catch (Exception $e) {
        error_log("Error en reserva: " . $e->getMessage());
        header("Location: ../view/confirmacion.php?status=error");
        exit;
    }
}
?>