<?php
require_once("repository.php");

class AppointmentDetailRepository extends SQLRepository
{
  public function __construct()
  {
    parent::__construct("AppointmentDetail", "id");
  }

  protected function insertTuple(): string
  {
    return "(:id,:f,:s)";
  }

  protected function bindInsert(PDOStatement $stmt, IEntity $data): void
  {
    if (!$data instanceof AppointmentDetail)
      throw new Exception("\$data debe ser de tipo AppointmentDetail");

    $stmt->bindValue(":id", $data->id);
    $stmt->bindValue(":f", $data->folio);
    $stmt->bindValue(":s", $data->serviceId);
  }

  protected function updateFields(): string
  {
    return "folio = :f, service_id = :s";
  }

  protected function bindUpdate(PDOStatement $stmt, IEntity $data): void
  {
    if (!$data instanceof AppointmentDetail)
      throw new Exception("\$data debe ser de tipo AppointmentDetail");

    $stmt->bindValue(":f", $data->folio);
    $stmt->bindValue(":s", $data->serviceId);
  }

  protected function whereParams(IEntityCriteria $criteria): string
  {
    if (!$criteria instanceof AppointmentDetailCriteria)
      throw new Exception("\$criteria debe ser de tipo AppointmentCriteria");

    $params = [];

    if (!is_null($criteria->folio))
      $params[] = "folio = :f";

    if (!is_null($criteria->serviceId))
      $params[] = "service_id = :s";

    if (count($params) > 0) return " WHERE " . implode(" AND ", $params);

    return "";
  }

  protected function bindCriteria(PDOStatement $stmt, IEntityCriteria $criteria): void
  {
    if (!$criteria instanceof AppointmentDetailCriteria)
      throw new Exception("\$criteria debe ser de tipo AppointmentCriteria");

    if (!is_null($criteria->folio))
      $stmt->bindValue(":f", $criteria->folio);

    if (!is_null($criteria->serviceId))
      $stmt->bindValue(":s", $criteria->serviceId);
  }

  protected function fromAssocArray(array $arr): IEntity
  {
    return new AppointmentDetail(
      $arr['id'],
      $arr['folio'],
      $arr['service_id']
    );
  }
}
