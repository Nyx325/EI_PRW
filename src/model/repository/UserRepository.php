<?php

require_once(dirname(__FILE__) . "/connector.php");
require_once(dirname(__FILE__) . "/Repository.php");
require_once(dirname(__FILE__) . "/../entity/User.php");

class UserRepository implements Repository
{
  private readonly Connector $connector;

  public function __construct()
  {
    $this->connector = Connector::getInstance();
  }

  /**
   * Agregar un nuevo usuario a la base de datos.
   * 
   * @param Entity $data
   * @throws Exception
   */
  public function add(Entity $data)
  {
    if (!($data instanceof User)) {
      throw new Exception("\$data debe ser un usuario");
    }

    $query = "INSERT INTO Users (usr, pwd, type) VALUES (:usr, :pwd, :type)";
    $connection = $this->connector->getConnection();

    $stmt = $connection->prepare($query);
    $stmt->bindParam(':usr', $data->usr);
    $stmt->bindParam(':pwd', $data->pwd);
    $stmt->bindParam(':type', $data->type);

    $stmt->execute();
  }

  /**
   * Eliminar un usuario por su ID.
   * 
   * @param int|string $id
   * @throws Exception
   */
  public function delete(int|string $id)
  {
    if (empty($id)) {
      throw new Exception("El ID no puede estar vacío.");
    }

    $query = "DELETE FROM Users WHERE id = :id";
    $connection = $this->connector->getConnection();

    $stmt = $connection->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    $stmt->execute();
  }

  /**
   * Obtener un usuario por su ID.
   * 
   * @param int|string $id
   * @return Entity|null
   */
  public function get(int|string $id): Entity|null
  {
    if (empty($id)) {
      return null;
    }

    $query = "SELECT * FROM Users WHERE id = :id LIMIT 1";
    $connection = $this->connector->getConnection();

    $stmt = $connection->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
      return User::fromAssocArray($result);
    }

    return null;
  }

  /**
   * Obtener todos los usuarios.
   * 
   * @return array
   */
  public function getAll(): array
  {
    $query = "SELECT * FROM Users";
    $connection = $this->connector->getConnection();

    $stmt = $connection->query($query);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $users = [];
    foreach ($result as $row) {
      $users[] = User::fromAssocArray($row);
    }

    return $users;
  }

  /**
   * Actualizar un usuario.
   * 
   * @param Entity $data
   * @throws Exception
   */
  public function update(Entity $data)
  {
    if (!($data instanceof User)) {
      throw new Exception("\$data debe ser un usuario");
    }

    $query = "UPDATE Users SET usr = :usr, pwd = :pwd, type = :type WHERE id = :id";
    $connection = $this->connector->getConnection();

    $stmt = $connection->prepare($query);
    $stmt->bindParam(':id', $data->getId(), PDO::PARAM_INT);
    $stmt->bindParam(':usr', $data->usr);
    $stmt->bindParam(':pwd', $data->pwd);
    $stmt->bindParam(':type', $data->type);

    $stmt->execute();
  }

  public function getUser(string $usr, string $pwd): ?User
  {
    $query = "SELECT * FROM Users WHERE usr = :usr LIMIT 1";
    $conn = $this->connector->getConnection();
    $stmt = $conn->prepare($query);

    // Vincular parámetros
    $stmt->bindParam(":usr", $usr);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el resultado
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si se encontró un usuario
    if ($result && password_verify($pwd, $result['pwd'])) {
      return User::fromAssocArray($result); // Crear el usuario desde el array
    }

    // Retornar null si no hay coincidencia
    return null;
  }
}
