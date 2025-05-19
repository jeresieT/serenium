-- creacion de SQL

CREATE TABLE Usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    contraseña VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'cliente') NOT NULL
);

CREATE TABLE Paquetes (
    id_paquete INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    duracion VARCHAR(50) NOT NULL,
    imagen VARCHAR(255) NOT NULL
);

CREATE TABLE Reservas (
    id_reserva INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_paquete INT NOT NULL,
    fecha_reserva DATE NOT NULL,
    estado ENUM('pendiente', 'confirmada', 'cancelada') DEFAULT 'pendiente',
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario),
    FOREIGN KEY (id_paquete) REFERENCES Paquetes(id_paquete)
);


-- datos de prueba
-- Insertar usuarios (administrador y clientes)
INSERT INTO Usuarios (nombre, apellido, email, contraseña, rol) VALUES
('Admin Jeresie', 'Admin Torres' , 'jeresietorres.alum@insestatut.cat', '$2y$10$kRHh2FLVCSGvAeja6Hgkbe8Pylz08fanfmWISNk822KE1Y9p0Td5q', 'admin'),
('Sofia', 'Ortega', 'sofia.ortega@gmail.com', 'cliente123', 'cliente'),
('Alia', 'Ortiz', 'alia.ortiz@gmail.com', 'cliente456', 'cliente');

-- Insertar paquetes de spa
INSERT INTO Paquetes (nombre, descripcion, precio, duracion, imagen) VALUES
('Relajación Básica', 'Masaje sueco de 60 minutos con aromaterapia.', 50, '60 minutos', 'http://www.cosmetologas.com/data/img_cont/img_imagenes/img_gr/430.jpg'),
('Experiencia Profunda', 'Masaje de tejido profundo 90 min con piedras calientes.', 80, '90 minutos', 'https://media.loveitopcdn.com/41147/massage-body-nam-gan-day-1.png'),
('Lujo Completo', 'Masaje completo 120 min, exfoliación y aromaterapia premium.', 120, '120 minutos', 'https://aurumwellbeing.com/wp-content/uploads/2021/11/relaxation-g661dae06b_1280.jpg');


INSERT INTO Reservas (id_usuario, id_paquete, fecha_reserva, estado) VALUES
(1, 2, '2025-04-05', 'pendiente'),
(2, 3, '2025-04-06', 'confirmada'),
(3, 1, '2025-04-19', 'cancelada');


