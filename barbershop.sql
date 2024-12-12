DROP DATABASE IF EXISTS barbershop;
CREATE DATABASE barbershop;
USE barbershop;

-- Tabla de usuarios
CREATE TABLE User (
    email VARCHAR(255) PRIMARY KEY NOT NULL,
    pwd VARCHAR(255) NOT NULL,
    user_type ENUM('ADMIN', 'CLIENT') NOT NULL DEFAULT 'CLIENT'
);

-- Tabla de servicios
CREATE TABLE Service (
    id INT PRIMARY KEY AUTO_INCREMENT,
    description TEXT NOT NULL,
    price DECIMAL(6,2) NOT NULL
);

-- Tabla de horarios
CREATE TABLE Schedule (
    day_of_week ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday') PRIMARY KEY,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL
);

-- Tabla de citas
CREATE TABLE Appointment (
    folio INT PRIMARY KEY AUTO_INCREMENT,
    phone TEXT NOT NULL,
    appointment_date DATETIME NOT NULL,
    email VARCHAR(255),
    FOREIGN KEY (email) REFERENCES User(email) ON DELETE SET NULL ON UPDATE CASCADE
);

-- Tabla de detalles de cita
CREATE TABLE AppointmentDetail (
    id INT PRIMARY KEY AUTO_INCREMENT,
    folio INT, -- FK con Appointment
    service_id INT, -- FK con Service
    FOREIGN KEY (folio) REFERENCES Appointment(folio) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (service_id) REFERENCES Service(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Insertar datos en la tabla User
INSERT INTO User (email, pwd, user_type) VALUES
('juan_perez@correo.com', 'contraseña123', 'CLIENT'),
('maria_gomez@correo.com', 'segurapass', 'CLIENT'),
('lucia_rojas@correo.com', 'micontraseña', 'CLIENT'),
('roberto_soto@correo.com', 'clave_secreta', 'CLIENT'),
('carlos_fernandez@correo.com', 'adminclave', 'ADMIN'),
('ana_blanco@correo.com', 'trabajopass', 'ADMIN'),
('miguel_negro@correo.com', 'barbero123', 'ADMIN'),
('laura_verde@correo.com', 'cortepeinado', 'CLIENT'),
('emma_gris@correo.com', 'cliente99', 'CLIENT'),
('daniel_azul@correo.com', 'trabajoseguro', 'ADMIN');

INSERT INTO Service (description, price) VALUES
('Corte de cabello', 150.00),
('Afeitado', 100.00),
('Corte y afeitado', 200.00),
('Recorte de barba', 120.00),
('Tinte para cabello', 250.00),
('Corte infantil', 100.00),
('Tratamiento capilar', 300.00),
('Masaje capilar', 180.00),
('Arreglo masculino', 220.00),
('Peinado femenino', 350.00);

INSERT INTO Schedule (day_of_week, start_time, end_time) VALUES
('Monday', '09:00:00', '18:00:00'),
('Tuesday', '09:00:00', '18:00:00'),
('Wednesday', '09:00:00', '18:00:00'),
('Thursday', '09:00:00', '18:00:00'),
('Friday', '09:00:00', '18:00:00'),
('Saturday', '10:00:00', '14:00:00'),
('Sunday', '10:00:00', '14:00:00');

INSERT INTO Appointment (phone, appointment_date, email) VALUES
('555-1234', '2024-12-01 09:00:00', 'juan_perez@correo.com'),
('555-5678', '2024-12-01 09:30:00', 'maria_gomez@correo.com'),
('555-8765', '2024-12-02 10:00:00', 'lucia_rojas@correo.com'),
('555-4321', '2024-12-02 10:30:00', 'roberto_soto@correo.com'),
('555-3456', '2024-12-03 11:00:00', 'carlos_fernandez@correo.com'),
('555-7890', '2024-12-04 11:30:00', 'ana_blanco@correo.com'),
('555-1122', '2024-12-05 12:00:00', 'miguel_negro@correo.com'),
('555-2233', '2024-12-06 12:30:00', 'laura_verde@correo.com'),
('555-3344', '2024-12-07 13:00:00', 'emma_gris@correo.com'),
('555-4455', '2024-12-08 13:30:00', 'daniel_azul@correo.com');

INSERT INTO AppointmentDetail (id, folio, service_id) VALUES
(0 ,1, 1),  -- Juan Perez, Corte de cabello
(0 ,1, 2),  -- Juan Perez, Afeitado
(0 ,2, 3),  -- Maria Gomez, Corte y afeitado
(0 ,2, 4),  -- Maria Gomez, Recorte de barba
(0 ,3, 1),  -- Lucia Rojas, Corte de cabello
(0 ,3, 5),  -- Lucia Rojas, Tinte para cabello
(0 ,4, 6),  -- Roberto Soto, Corte infantil
(0 ,5, 7),  -- Carlos Fernandez, Tratamiento capilar
(0 ,6, 8),  -- Ana Blanco, Masaje capilar
(0 ,7, 9),  -- Miguel Negro, Arreglo masculino
(0 ,8, 10), -- Laura Verde, Peinado femenino
(0 ,9, 1),  -- Emma Gris, Corte de cabello
(0 ,9, 3),  -- Emma Gris, Corte y afeitado
(0 ,10, 4), -- Daniel Azul, Recorte de barba
(0 ,10, 2); -- Daniel Azul, Afeitado
