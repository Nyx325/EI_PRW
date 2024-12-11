<?php
require("repository.php");

class UserRepository extends SQLRepository
{
  public function __construct()
  {
    parent::__construct("User", "email");
  }

  private function validateInstance(IEntity $data)
  {
    if (!$data instanceof User) throw new Exception("\$data debe ser de tipo User");
  }

  protected function insertTuple(): string
  {
    return "(:email, :pwd, :type)";
  }

  protected function bindInsert(PDOStatement $stmt, IEntity $data): void
  {
    $this->validateInstance($data);
    $stmt->bindParam(":email", $data->email);
    $stmt->bindParam(":pwd", $data->pwd);
    $stmt->bindParam(":type", $data->type);
  }

  protected function updateFields(): string
  {
    return "pwd = :pwd, type = :type";
  }

  protected function bindUpdate(PDOStatement $stmt, IEntity $data): void
  {
    if (!$data instanceof User) return;

    $stmt->bindParam(":pwd", $data->pwd);
    $stmt->bindParam(":type", $data->type);
  }

  protected function fromAssocArray(array $arr): IEntity
  {
    return new User(
      $arr["email"],
      $arr["pwd"],
      $arr["type"]
    );
  }

  protected function whereParams(IEntityCriteria $criteria): string
  {
    $params = [];

    if (!$criteria instanceof UserCriteria) throw new Exception("\$criteria debe ser de tipo UserCriteria");

    if (!is_null($criteria->email)) $params[] = "email LIKE :email";
    if (!is_null($criteria->type)) $params[] = "type = :type";

    if (count($params) > 0) return " WHERE " . implode(" AND ", $params);

    return "";
  }

  protected function bindCriteria(PDOStatement $stmt, IEntityCriteria $criteria): void
  {
    if (!$criteria instanceof UserCriteria) {
      throw new Exception("\$criteria debe ser de tipo UserCriteria");
    }

    $stmt->bindParam(":email", '%' . $criteria->email . "%");
    $stmt->bindParam(":type", $criteria->type);
  }
}
