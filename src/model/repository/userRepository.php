<?php
require("repository.php");

class UserRepository extends SQLRepository
{
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
    return "email = :email, pwd = :pwd, type = :type";
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
}
