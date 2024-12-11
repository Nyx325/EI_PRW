<?php
require_once("repository.php");

class ServiceRepository extends SQLRepository
{
  public function __construct()
  {
    parent::__construct("Service", "id");
  }

  protected function insertTuple(): string
  {
    return "(:id,:description,:price)";
  }

  protected function bindInsert(PDOStatement $stmt, IEntity $data): void
  {
    if (!$data instanceof Service)
      throw new Exception("\$data debe ser un tipo Service");

    $stmt->bindValue(":id", $data->id);
    $stmt->bindValue(":description", $data->description);
    $stmt->bindValue(":price", $data->price);
  }

  protected function updateFields(): string
  {
    return "description = :desc, price = :price";
  }

  protected function bindUpdate(PDOStatement $stmt, IEntity $data): void
  {
    if (!$data instanceof Service)
      throw new Exception("\$data debe ser un tipo Service");
  }

  protected function fromAssocArray(array $arr): IEntity
  {
    return new Service(
      $arr['id'],
      $arr['description'],
      $arr['price']
    );
  }

  protected function whereParams(IEntityCriteria $criteria): string
  {
    $params = [];
    if (!$criteria instanceof ServiceCriteria)
      throw new Exception("\$criteria debe ser de tipo ServiceCriteria");

    if (!is_null($criteria->description))
      $params[] = "description LIKE :desc";

    if (!is_null($criteria->price))
      $params[] = "price = :price";

    if (count($params) > 0) return " WHERE " . implode(" AND ", $params);

    return "";
  }

  protected function bindCriteria(PDOStatement $stmt, IEntityCriteria $criteria): void
  {
    if (!$criteria instanceof ServiceCriteria)
      throw new Exception("\$criteria debe ser de tipo ServiceCriteria");

    if (!is_null($criteria->description))
      $stmt->bindValue(":desc", '%' . $criteria->description . '%');

    if (!is_null($criteria->price))
      $stmt->bindValue(":price", $criteria->price);
  }
}
