<?php
require("../model/repository/AppointmentRepository.php");

header('Content-Type: application/json'); // Configura la respuesta como JSON

// Asegúrate de capturar el método HTTP
$method = $_SERVER['REQUEST_METHOD'];

// Crear una instancia del repositorio
$repository = new AppointmentRepository();

try {
    switch ($method) {
        case 'GET':
            // Si se envía un parámetro 'id', obtener un solo registro
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $appointment = $repository->get($id);

                if ($appointment) {
                    echo json_encode($appointment);
                } else {
                    http_response_code(404);
                    echo json_encode(["error" => "Cita no encontrada"]);
                }
            } else {
                // Obtener todas las citas si no se especifica un 'id'
                $appointments = $repository->getAll();
                echo json_encode($appointments);
            }
            break;

        case 'POST':
            // Leer los datos del cuerpo de la solicitud
            $data = json_decode(file_get_contents("php://input"), true);

            if (isset($data['appointment_date'], $data['user'])) {
                $appointment = new Appointment(0, $data['appointment_date'], $data['user']);
                $repository->add($appointment);
                echo json_encode(["success" => "Cita creada correctamente"]);
            } else {
                http_response_code(400);
                echo json_encode(["error" => "Datos incompletos"]);
            }
            break;

        case 'PUT':
            // Leer los datos del cuerpo de la solicitud
            $data = json_decode(file_get_contents("php://input"), true);

            if (isset($data['id'], $data['appointment_date'], $data['user'])) {
                $appointment = new Appointment($data['id'], $data['appointment_date'], $data['user']);
                $repository->update($appointment);
                echo json_encode(["success" => "Cita actualizada correctamente"]);
            } else {
                http_response_code(400);
                echo json_encode(["error" => "Datos incompletos"]);
            }
            break;

        case 'DELETE':
            // Leer el parámetro 'id' para eliminar
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $repository->delete($id);
                echo json_encode(["success" => "Cita eliminada correctamente"]);
            } else {
                http_response_code(400);
                echo json_encode(["error" => "ID requerido para eliminar"]);
            }
            break;

        default:
            // Método no permitido
            http_response_code(405);
            echo json_encode(["error" => "Método no permitido"]);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
