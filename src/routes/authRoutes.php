<?php
require_once("../controller/authController.php");

class AuthRoutes
{
  private readonly AuthController $ctrl;

  public function __construct()
  {
    $this->ctrl = new AuthController();
  }


  /**
   * Maneja una solicitud HTTP y ejecuta la acción correspondiente según el método.
   * 
   * @return void
   */
  public function handleRequest()
  {
    try {
      header('Content-Type: application/json'); // Configura la respuesta como JSON

      $method = $_SERVER['REQUEST_METHOD'];
      $reqBody = json_decode(file_get_contents("php://input"), true);

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
          echo json_encode(["error" => "Método no permitido"]);
          break;
      }
    } catch (UserVisibleException $e) {
      http_response_code(400);
      echo json_encode(["error" => "Error: " . $e->getMessage()]);
    } catch (\Throwable $e) {
      error_log($e->getMessage() . "\n");
      error_log($e->getTraceAsString() . "\n");
      http_response_code(500);
      echo json_encode(["error" => "Internal server error"]);
    }
  }

  protected function isLogged()
  {
    $usr = $this->ctrl->isLogged();
    if (is_null($usr)) {
      http_response_code(401);
      echo json_encode(["message" => "No autenticado. Por favor iniciar sesión"]);
      return;
    }

    http_response_code(200);
    echo json_encode($usr);
  }

  protected function auth($r)
  {
    if (!isset($r['email'], $r['pwd']))
      throw new UserVisibleException("Se debe especificar 'email' y 'pwd'");

    if ($this->ctrl->auth($r['email'], $r['pwd'])) {
      http_response_code(200);
      echo json_encode(["message" => "Inicio de sesión correcto"]);
    } else {
      http_response_code(401);
      echo json_encode(["error" => "Credenciales incorrectas"]);
    }
  }
}

$routes = new AuthRoutes();
$routes->handleRequest();
