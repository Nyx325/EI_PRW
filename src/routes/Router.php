<?php

require("../model/repository/Repository.php");

abstract class Router
{
    protected readonly Repository $repo;
    protected readonly string $valueName;

    public function __construct(Repository $repo, string $valueName)
    {
        $this->valueName = $valueName;
        $this->repo = $repo;
    }

    public function handleRequest()
    {
        header('Content-Type: application/json'); // Configura la respuesta como JSON

        // Asegúrate de capturar el método HTTP
        $method = $_SERVER['REQUEST_METHOD'];
        $reqBody = json_decode(file_get_contents("php://input"), true);

        try {
            switch ($method) {
                case 'GET':
                    $this->get();
                    break;

                case 'POST':
                    $this->add($reqBody);
                    break;

                case 'PUT':
                    $this->update($reqBody);
                    break;

                case 'DELETE':
                    $this->delete();
                    break;

                default:
                    // Método no permitido
                    http_response_code(405);
                    echo json_encode(["error" => "Método no permitido"]);
                    break;
            }
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode(["error" => "Internal server error: " . $e->getMessage()]);
        }
    }

    protected function get()
    {
        // Si se envía un parámetro 'id', obtener un solo registro
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $appointment = $this->repo->get($id);

            if ($appointment)
                echo json_encode($appointment);
            else {
                http_response_code(404);
                echo json_encode(["error" => $this->valueName . " no encontrada"]);
            }
        } else
            echo json_encode($this->repo->getAll());
    }

    protected function delete()
    {
        // Leer el parámetro 'id' para eliminar
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $this->repo->delete($id);
            echo json_encode(["success" => $this->valueName . " eliminada correctamente"]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "ID requerido para eliminar"]);
        }
    }

    protected abstract function add($reqBody);
    protected abstract function update($reqBody);
}
