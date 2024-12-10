<?php

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
 * Clase abstracta para los repositorios que interactúan con la base de datos usando SQL.
 * Implementa métodos generales para agregar, actualizar, eliminar y obtener entidades.
 */
abstract class SQLRepository implements IRepository
{
  /** @var Connector $connector Instancia del conector a la base de datos */
  protected readonly Connector $connector;

  /** @var string $table Nombre de la tabla en la base de datos */
  protected readonly string $table;

  /** @var string $table Nombre del campo que representa la PRIMARY KEY */
  protected readonly string $idField;

  /**
   * Constructor de la clase SQLRepository.
   *
   * @param string $table Nombre de la tabla en la base de datos con la que se va a interactuar.
   */
  public function __construct(string $table, string $idField)
  {
    $this->idField = $idField;
    $this->table = $table;
    $this->connector = Connector::getInstance();
  }

  /**
   * Agrega una nueva entidad a la base de datos.
   *
   * Este método construye una consulta SQL `INSERT INTO` y ejecuta la inserción de los datos
   * correspondientes a la entidad proporcionada.
   *
   * @param IEntity $data La entidad que se va a agregar.
   *
   * @return void
   */
  public function add(IEntity $data): void
  {
    $conn = $this->connector->getConnection();
    $query = "INSERT INTO " . $this->table . " VALUES " . $this->insertTuple();
    $stmt = $conn->prepare($query);
    $this->bind($stmt, $data);
    $stmt->execute();
  }

  public function update(IEntity $data): void
  {
    $conn = $this->connector->getConnection();
    $query = "UPDATE " . $this->table . " SET " . $this->updateFields() . " WHERE " . $this->idField . " = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":id", $data->getId());
    $this->bind($stmt, $data);
    $stmt->execute();
  }

  public function delete(int|string $id): void
  {
    $conn = $this->connector->getConnection();
    $query = "DELETE " . $this->table . " WHERE " . $this->idField . " = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
  }

  public function get(int|string $id): ?IEntity
  {
    // Conexión a la base de datos
    $conn = $this->connector->getConnection();

    // Construir la consulta SQL
    $query = "SELECT * FROM " . $this->table . " WHERE " . $this->idField . " = :id LIMIT 1";
    $stmt = $conn->prepare($query);

    // Enlazar el parámetro `id`
    $stmt->bindParam(":id", $id);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el resultado como un array asociativo
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si no hay resultados, retornar null
    if ($result === false) {
      return null;
    }

    // Si hay un resultado, convertirlo a una entidad usando fromAssocArray
    return $this->fromAssocArray($result);
  }

  public function getBy(?IEntityCriteria $criteria): array
  {
    // Conexión a la base de datos
    $conn = $this->connector->getConnection();

    // Inicializar la consulta base SELECT
    $query = "SELECT * FROM " . $this->table;

    // Si se proporciona un criterio (no es null)
    if ($criteria !== null) {
      // Obtener el array asociativo de campos y valores desde el criterio
      $fields = $criteria->toAssocArray();

      // Si hay criterios, construir la cláusula WHERE
      if (!empty($fields)) {
        $whereClauses = [];
        $values = [];

        // Construir la lista de condiciones WHERE
        foreach ($fields as $field => $value) {
          $whereClauses[] = $field . " = :" . $field;
          $values[":" . $field] = $value;
        }

        // Unir todas las condiciones WHERE con 'AND'
        $query .= " WHERE " . implode(" AND ", $whereClauses);
      }
    }

    // Preparar la consulta SQL
    $stmt = $conn->prepare($query);

    // Enlazar los valores
    foreach ($values as $placeholder => $value) {
      $stmt->bindParam($placeholder, $value);
    }

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener los resultados como un array asociativo
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Convertir cada fila en un objeto de tipo IEntity usando fromAssocArray
    $entities = [];
    foreach ($results as $row) {
      $entities[] = $this->fromAssocArray($row);
    }

    return $entities; // Retorna el array de objetos IEntity
  }

  /**
   * Método abstracto para generar la tupla de valores a insertar en la consulta SQL.
   *
   * Este método debe devolver un string con los placeholders para los valores de la entidad,
   * por ejemplo: `(:user, :pwd)` si la entidad tiene propiedades `user` y `pwd`.
   *
   * @return string La tupla de placeholders para la inserción (por ejemplo, `(:user, :pwd)`).
   */
  protected abstract function insertTuple(): string;

  /**
   * Método abstracto para generar los valores a actualizar en la consulta SQL.
   *
   * Este método debe devolver un string con los placeholders para los valores de la entidad,
   * por ejemplo: `usr = :user, pwd = :pwd` si la entidad tiene propiedades `user` y `pwd`.
   *
   * @return string La tupla de placeholders para la actualizacion (por ejemplo, `usr = :user, pwd = :pwd`).
   */
  protected abstract function updateFields(): string;

  /**
   * Método abstracto para enlazar los valores de la entidad a la consulta SQL.
   *
   * Este método debe asociar los valores de la entidad con los placeholders en la consulta SQL
   * usando `$stmt->bindParam()`.
   *
   * @param PDOStatement $stmt La declaración SQL preparada.
   * @param IEntity $data La entidad cuyos valores se van a asociar con los placeholders.
   *
   * @return void
   */
  protected abstract function bind(PDOStatement $stmt, IEntity $data): void;

  /**
   * Método que instancia el objeto correspondiente a partir de un array asociativo
   *
   * @return IEntity El objeto instanciado a partir de la consulta SQL
   */
  protected abstract function fromAssocArray(array $arr): IEntity;
}
