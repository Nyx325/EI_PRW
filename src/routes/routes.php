<?php

/**
 * Clase abstracta Routes
 * 
 * Esta clase proporciona una base para manejar solicitudes HTTP de forma genérica,
 * utilizando controladores para interactuar con entidades.
 * Define métodos abstractos que deben implementarse en clases derivadas para manejar
 * datos específicos de entidades.
 */
require_once("../controller/controller.php");

abstract class Routes
{
  /** @var Controller Controlador asociado con el router */
  protected readonly Controller $ctrl;

  /** @var string Campo identificador principal de la entidad */
  protected readonly string $idField;

  /** @var string Nombre de la entidad asociada */
  protected readonly string $entityName;

  /**
   * Constructor de la clase Router.
   * 
   * @param Controller $ctrl       Instancia del controlador.
   * @param string     $idField    Nombre del campo identificador principal.
   * @param string     $entityName Nombre de la entidad manejada.
   */
  public function __construct(Controller $ctrl, string $idField, string $entityName)
  {
    $this->entityName = $entityName;
    $this->idField = $idField;
    $this->ctrl = $ctrl;
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
          $this->get();
          break;

        case 'POST':
          $data = $this->addDataFromBody($reqBody);
          $item = $this->ctrl->add($data);
          echo json_encode($item);
          break;

        case 'PUT':
          $data = $this->updateDataFromBody($reqBody);
          $this->ctrl->update($data);
          break;

        case 'DELETE':
          $this->delete();
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

  /**
   * Maneja solicitudes HTTP GET para obtener datos de la entidad.
   * 
   * @return void
   */
  protected function get()
  {
    if (isset($_GET[$this->idField])) {
      $id = $_GET[$this->idField];
      $entity = $this->ctrl->get($id);

      if ($entity) {
        echo json_encode($entity);
      } else {
        http_response_code(404);
        echo json_encode(["error" => $this->entityName . " no encontrado"]);
      }
    } else {
      $criteria = $this->criteriaFromParams($_GET);
      echo json_encode($this->ctrl->getBy($criteria));
    }
  }

  /**
   * Maneja solicitudes HTTP DELETE para eliminar una entidad.
   * 
   * @return void
   */
  protected function delete()
  {
    if (isset($_GET[$this->idField])) {
      $id = $_GET[$this->idField];
      $this->ctrl->delete($id);
      echo json_encode(["success" => $this->entityName . " eliminada correctamente"]);
    } else {
      http_response_code(400);
      echo json_encode(["error" => "ID requerido para eliminar"]);
    }
  }

  /**
   * Método abstracto para obtener datos de agregar desde el cuerpo de la solicitud.
   * 
   * @param array $reqBody Cuerpo de la solicitud HTTP.
   * @return IEntity Instancia de la entidad generada.
   */
  protected abstract function addDataFromBody($reqBody): IEntity;

  /**
   * Método abstracto para obtener datos de actualización desde el cuerpo de la solicitud.
   * 
   * @param array $reqBody Cuerpo de la solicitud HTTP.
   * @return IEntity Instancia de la entidad generada.
   */
  protected abstract function updateDataFromBody($reqBody): IEntity;

  /**
   * Método abstracto para obtener criterios de búsqueda desde los parámetros de solicitud.
   * 
   * @return IEntityCriteria Instancia de los criterios de búsqueda.
   */
  protected abstract function criteriaFromParams($reqParams): IEntityCriteria;
}
