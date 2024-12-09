<?php
/*
Usuario autenticado correctamente:
    200 OK: Si la autenticación es correcta y deseas 
    devolver datos relevantes al cliente.
    204 No Content: Si la autenticación es correcta 
    pero no hay contenido que devolver.
Usuario no autenticado:
    401 Unauthorized: Este código indica que el 
    cliente no está autenticado. Generalmente se usa
    cuando falta un token de autenticación o el
    proporcionado es inválido.
*/
require("../controller/SessionController.php");

class AuthRouter
{
    protected readonly SessionController $ctrl;

    public function __construct()
    {
        $this->ctrl = new SessionController();
    }

    public function handleRequest()
    {
        header('Content-Type: application/json'); // Configura la respuesta como JSON

        $method = $_SERVER['REQUEST_METHOD'];
        $reqBody = json_decode(file_get_contents("php://input"), true);

        try {
            switch ($method) {
                case 'GET':
                    $this->isLogged();
                    break;
                case 'POST':
                    $this->auth($reqBody);
                    break;
                case 'DELETE':
                    $this->ctrl->logOut();
                    break;

                default:
                    http_response_code(405);
                    echo json_encode(["error" => "Método no admitido"]);
                    break;
            }
        } catch (\Throwable $tr) {
            http_response_code(500);
            echo json_encode(["error" => "Internal server error: " . $tr->getMessage()]);
        }
    }

    protected function isLogged()
    {
        $usr = $this->ctrl->isLogged();
        if (is_null($usr)) {
            http_response_code(401);
            echo json_encode(["error" => "Usuario no autenticado"]);
            return;
        }

        http_response_code(201);
        echo json_encode($usr);
    }

    protected function auth($reqBody)
    {
        if (!isset($reqBody['usr'], $reqBody['pwd'])) {
            http_response_code(400);
            echo json_encode(["error" => "Se debe definir 'usr' y 'pwd'"]);
            return;
        }

        $valid = $this->ctrl->logIn($reqBody['usr'], $reqBody['pwd']);

        if ($valid) {
            http_response_code(200);
            echo json_encode(["message" => "Inicio de sesión exitoso"]);
        } else {
            http_response_code(401);
            echo json_encode(["message" => "Credenciales incorrectas"]);
        }
    }
}

$router = new AuthRouter();
$router->handleRequest();