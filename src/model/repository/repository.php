<?php
require_once("connector.php");

/**
 * Interfaz de repositorio general para manejar las operaciones CRUD (Crear, Leer, Actualizar, Eliminar)
 * sobre las entidades.
 */

interface IRepository
{
  /**
   * Agrega una nueva entidad a la base de datos.
   *
   * @param IEntity $data La entidad que se va a agregar.
   *
   * @return void
   */
  public function add(IEntity $data): void;

  /**
   * Actualiza una entidad existente en la base de datos.
   *
   * @param IEntity $data La entidad con los nuevos valores para actualizar.
   *
   * @return void
   */
  public function update(IEntity $data): void;

  /**
   * Elimina una entidad de la base de datos mediante su identificador.
   *
   * @param int|string $id El identificador de la entidad a eliminar.
   *
   * @return void
   */
  public function delete(int|string $id): void;

  /**
   * Obtiene una entidad de la base de datos por su identificador.
   *
   * @param int|string $id El identificador de la entidad.
   *
   * @return IEntity La entidad correspondiente al identificador dado.
   */
  public function get(int|string $id): ?IEntity;

  /**
   * Obtiene un conjunto de entidades filtradas por criterios específicos.
   *
   * @param IEntityCriteria|null $criteria Los criterios de búsqueda. Si es `null`, no se aplican filtros.
   *
   * @return array Un arreglo de entidades que coinciden con los criterios.
   */
  public function getBy(?IEntityCriteria $criteria): array;
}

/**
 * Clase abstracta para interactuar con una base de datos relacional utilizando SQL.
 */
abstract class SQLRepository implements IRepository
{
  /** @var Connector $connector Instancia del conector a la base de datos */
  protected readonly Connector $connector;

  /** @var string $table Nombre de la tabla en la base de datos */
  protected readonly string $table;

  /** @var string $idField Nombre del campo que representa la PRIMARY KEY */
  protected readonly string $idField;

  /**
   * Constructor de la clase SQLRepository.
   *
   * @param string $table Nombre de la tabla en la base de datos con la que se va a interactuar.
   * @param string $idField Nombre del campo que actúa como PRIMARY KEY.
   */
  public function __construct(string $table, string $idField)
  {
    $this->idField = $idField;
    $this->table = $table;
    $this->connector = Connector::getInstance();
  }

  public function add(IEntity $data): void
  {
    $conn = $this->connector->getConnection();
    $query = "INSERT INTO " . $this->table . " VALUES " . $this->insertTuple();
    $stmt = $conn->prepare($query);
    $this->bindInsert($stmt, $data);
    $stmt->execute();
  }

  public function update(IEntity $data): void
  {
    $conn = $this->connector->getConnection();
    $query = "UPDATE " . $this->table . " SET " . $this->updateFields() . " WHERE " . $this->idField . " = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":id", $data->getId());
    $this->bindUpdate($stmt, $data);
    $stmt->execute();
  }

  public function delete(int|string $id): void
  {
    $conn = $this->connector->getConnection();
    $query = "DELETE FROM " . $this->table . " WHERE " . $this->idField . " = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
  }

  public function get(int|string $id): ?IEntity
  {
    $conn = $this->connector->getConnection();
    $query = "SELECT * FROM " . $this->table . " WHERE " . $this->idField . " = :id LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result === false) {
      return null;
    }

    return $this->fromAssocArray($result);
  }

  public function getBy(?IEntityCriteria $criteria): array
  {
    $conn = $this->connector->getConnection();
    $query = "SELECT * FROM " . $this->table;

    if ($criteria !== null) {
      $query .= $this->whereParams($criteria);
    }

    $stmt = $conn->prepare($query);

    if ($criteria !== null)
      $this->bindCriteria($stmt, $criteria);


    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $entities = [];
    foreach ($results as $row) {
      $entities[] = $this->fromAssocArray($row);
    }

    return $entities;
  }

  /**
   * Genera una cadena de placeholders para los valores a insertar.
   *
   * @return string Tupla con placeholders (por ejemplo, `(:col1, :col2)`).
   */
  protected abstract function insertTuple(): string;

  /**
   * Genera una cadena de placeholders para los valores a actualizar.
   *
   * @return string Cadena con placeholders (por ejemplo, `col1 = :val1, col2 = :val2`).
   */
  protected abstract function updateFields(): string;

  /**
   * Genera una cláusula WHERE basada en los criterios especificados.
   *
   * @param IEntityCriteria $criteria Criterios para filtrar las entidades.
   *
   * @return string Cláusula WHERE para la consulta SQL.
   */
  protected abstract function whereParams(IEntityCriteria $criteria): string;

  /**
   * Enlaza los valores de una entidad a una consulta SQL de inserción.
   *
   * @param PDOStatement $stmt La declaración SQL preparada.
   * @param IEntity $data La entidad cuyos valores se enlazarán.
   *
   * @return void
   */
  protected abstract function bindInsert(PDOStatement $stmt, IEntity $data): void;

  /**
   * Enlaza los valores de una entidad a una consulta SQL de actualización.
   *
   * @param PDOStatement $stmt La declaración SQL preparada.
   * @param IEntity $data La entidad cuyos valores se enlazarán.
   *
   * @return void
   */
  protected abstract function bindUpdate(PDOStatement $stmt, IEntity $data): void;

  /**
   * Convierte un array asociativo en una instancia de entidad.
   *
   * @param array $arr Datos en forma de array asociativo.
   *
   * @return IEntity La entidad instanciada a partir de los datos.
   */
  protected abstract function fromAssocArray(array $arr): IEntity;

  /**
   * Enlaza los valores de los criterios a la consulta SQL.
   *
   * @param PDOStatement $stmt La declaración SQL preparada.
   * @param IEntityCriteria $criteria Los criterios cuyos valores se enlazarán.
   *
   * @return void
   */
  protected abstract function bindCriteria(PDOStatement $stmt, IEntityCriteria $criteria): void;
}
