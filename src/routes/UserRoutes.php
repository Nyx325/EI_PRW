<?php

require("Router.php");
require("../model/repository/UserRepository.php");

class UserRouter extends Router
{
    protected function add($reqBody)
    {
        if (isset($reqBody['usr'], $reqBody['pwd'], $reqBody['type'])) {
            $usr = new User(0, $reqBody['usr'], $reqBody['pwd'], $reqBody['type']);
            $this->repo->add($usr);
            echo json_encode(["success" => "Usuario creada correctamente"]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Se deben definir: 'usr', 'pwd' y 'type"]);
        }
    }

    protected function update($reqBody)
    {
        if (isset($reqBody['id'], $reqBody['usr'], $reqBody['pwd'], $reqBody['type'])) {
            $usr = new User($reqBody['id'], $reqBody['usr'], $reqBody['pwd'], $reqBody['type']);
            $this->repo->update($usr);
            echo json_encode(["success" => "Usuario actualizado correctamente"]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Se deben definir: 'id', 'usr', 'pwd' y 'type"]);
        }
    }
}

$router = new UserRouter(new UserRepository(), "Usuario");
$router->handleRequest();