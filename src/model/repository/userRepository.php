<?php
require_once("repository.php");

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
    $stmt->bindValue(":email", $data->email);
    $stmt->bindValue(":pwd", $data->pwd);
    $stmt->bindValue(":type", $data->type);
  }

  protected function updateFields(): string
  {
    return "pwd = :pwd, user_type = :type";
  }

  protected function bindUpdate(PDOStatement $stmt, IEntity $data): void
  {
    $this->validateInstance($data);
    $stmt->bindValue(":pwd", $data->pwd);
    $stmt->bindValue(":type", $data->type);
  }

  protected function fromAssocArray(array $arr): IEntity
  {
    return new User(
      $arr["email"],
      $arr["pwd"],
      $arr["user_type"]
    );
  }

  protected function whereParams(IEntityCriteria $criteria): string
  {
    $params = [];

    if (!$criteria instanceof UserCriteria) throw new Exception("\$criteria debe ser de tipo UserCriteria");

    if (!is_null($criteria->email)) $params[] = "email LIKE :email";
    if (!is_null($criteria->type)) $params[] = "user_type = :type";

    if (count($params) > 0) return " WHERE " . implode(" AND ", $params);

    return "";
  }

  protected function bindCriteria(PDOStatement $stmt, IEntityCriteria $criteria): void
  {
    if (!$criteria instanceof UserCriteria) {
      throw new Exception("\$criteria debe ser de tipo UserCriteria");
    }

    if (!is_null($criteria->email))
      $stmt->bindValue(":email", '%' . $criteria->email . "%");

    if (!is_null($criteria->type))
      $stmt->bindValue(":type", $criteria->type);
  }
}
