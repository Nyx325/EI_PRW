DROP DATABASE IF EXISTS barbershop;
CREATE DATABASE barbershop;
use barbershop;

CREATE TABLE Users(
  id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
  usr VARCHAR(16) NOT NULL,
  pwd VARCHAR(16) NOT NULL,
  type ENUM('employee', 'customer') NOT NULL
);

CREATE TABLE Services(
  id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
  description VARCHAR(30) NOT NULL,
  price DECIMAL(10,2) NOT NULL
);

CREATE TABLE Appointments(
  id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
  appointment_date DATETIME NOT NULL,
  user INT,
  service INT,
  FOREIGN KEY (user) REFERENCES Users(id) 
    ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (service) REFERENCES Services(id)
    ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE Schedules (
  id INT PRIMARY KEY AUTO_INCREMENT,
  day_of_week ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday') NOT NULL,
  start_time TIME NOT NULL,
  end_time TIME NOT NULL
);

-- Insertar datos en la tabla Users
INSERT INTO Users (usr, pwd, type) VALUES
('juan_perez', 'contraseña123', 'customer'),
('maria_gomez', 'segurapass', 'customer'),
('lucia_rojas', 'micontraseña', 'customer'),
('roberto_soto', 'clave_secreta', 'customer'),
('carlos_fernandez', 'adminclave', 'employee'),
('ana_blanco', 'trabajopass', 'employee'),
('miguel_negro', 'barbero123', 'employee'),
('laura_verde', 'cortepeinado', 'customer'),
('emma_gris', 'cliente99', 'customer'),
('daniel_azul', 'trabajoseguro', 'employee');

-- Insertar datos en la tabla Services
INSERT INTO Services (description, price) VALUES
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

-- Insertar datos en la tabla Appointments
INSERT INTO Appointments (appointment_date, user, service) VALUES
('2024-12-01 10:00:00', 1, 1),
('2024-12-01 11:00:00', 2, 2),
('2024-12-01 12:00:00', 3, 3),
('2024-12-02 09:00:00', 4, 4),
('2024-12-02 10:00:00', 5, 5),
('2024-12-03 11:30:00', 6, 6),
('2024-12-04 15:00:00', 7, 7),
('2024-12-05 13:00:00', 8, 8),
('2024-12-06 14:30:00', 9, 9),
('2024-12-07 16:00:00', 10, 10);

-- Insertar datos en la tabla Schedules (un registro por día)
INSERT INTO Schedules (day_of_week, start_time, end_time) VALUES
('Monday', '09:00:00', '17:00:00'),
('Tuesday', '09:00:00', '17:00:00'),
('Wednesday', '09:00:00', '17:00:00'),
('Thursday', '09:00:00', '17:00:00'),
('Friday', '09:00:00', '17:00:00'),
('Saturday', '10:00:00', '14:00:00'),
('Sunday', '11:00:00', '15:00:00');
