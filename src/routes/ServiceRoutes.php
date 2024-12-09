<?php

require("Router.php");
require("../model/repository/ServiceRepository.php");

class ServiceRouter extends Router
{
    protected function add($reqBody)
    {
        if (isset($reqBody['description'], $reqBody['price'])) {
            $service = new Service(0, $reqBody['description'], $reqBody['price']);
            $this->repo->add($service);
            echo json_encode(["success" => "Servicio creado correctamente"]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Se deben definir: 'description' y 'price'"]);
        }
    }

    protected function update($reqBody)
    {
        if (isset($reqBody['id'], $reqBody['description'], $reqBody['price'])) {
            $service = new Service($reqBody['id'], $reqBody['description'], $reqBody['price']);
            $this->repo->update($service);
            echo json_encode(["success" => "Servicio actualizado correctamente"]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Se deben definir: 'id', 'description', 'price'"]);
        }
    }
}

$router = new UserRouter(new UserRepository(), "Usuario");
$router->handleRequest();