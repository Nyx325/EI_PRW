<?php
require("Router.php");
require("../model/repository/AppointmentRepository.php");

class AppointmentRouter extends Router
{
    protected function add($reqBody)
    {
        if (isset($reqBody['appointment_date'], $reqBody['user'])) {
            $appointment = new Appointment(0, $reqBody['appointment_date'], $reqBody['user']);
            $this->repo->add($appointment);
            echo json_encode(["success" => "Cita creada correctamente"]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Se deben definir: 'appointment_date' y 'user'"]);
        }
    }

    protected function update($reqBody)
    {
        if (isset($reqBody['id'], $reqBody['appointment_date'], $reqBody['user'])) {
            $appointment = new Appointment($reqBody['id'], $reqBody['appointment_date'], $reqBody['user']);
            $this->repo->update($appointment);
            echo json_encode(["success" => "Cita actualizada correctamente"]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Se deben definir: 'id', 'appointment_date' y 'user'"]);
        }
    }
}

$router = new AppointmentRouter(new AppointmentRepository(), "Cita");
$router->handleRequest();
