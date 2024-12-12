<?php
require_once("repository.php");

class AppointmentRepository extends SQLRepository
{
  public function __construct()
  {
    parent::__construct("Appointment", "folio");
  }

  protected function insertTuple(): string
  {
    return "(:folio,:phone,:date,:email)";
  }

  protected function bindInsert(PDOStatement $stmt, IEntity $data): void
  {
    if (!$data instanceof Appointment)
      throw new Exception("\$data debe ser de tipo Appointment");

    $stmt->bindValue(":folio", $data->folio);
    $stmt->bindValue(":phone", $data->phone);
    $stmt->bindValue(":date", $data->date);
    $stmt->bindValue(":email", $data->email);
  }

  protected function updateFields(): string
  {
    return "phone = :p, appointment_date = :d, email = :e";
  }

  protected function bindUpdate(PDOStatement $stmt, IEntity $data): void
  {
    if (!$data instanceof Appointment)
      throw new Exception("\$data debe ser de tipo Appointment");

    $stmt->bindValue(":p", $data->phone);
    $stmt->bindValue(":d", $data->date);
    $stmt->bindValue(":e", $data->email);
  }

  protected function whereParams(IEntityCriteria $criteria): string
  {
    if (!$criteria instanceof AppointmentCriteria)
      throw new Exception("\$criteria debe ser de tipo AppointmentCriteria");

    $params = [];

    if (!is_null($criteria->email))
      $params[] = "email LIKE :e";

    if (!is_null($criteria->phone))
      $params[] = "phone LIKE :p";

    if (!is_null($criteria->date))
      $params[] = "appointment_date = :d";

    if (count($params) > 0) return " WHERE " . implode(" AND ", $params);

    return "";
  }

  protected function bindCriteria(PDOStatement $stmt, IEntityCriteria $criteria): void
  {
    if (!$criteria instanceof AppointmentCriteria)
      throw new Exception("\$criteria debe ser de tipo AppointmentCriteria");

    if (!is_null($criteria->email))
      $stmt->bindValue(":e", '%' . $criteria->email . '%');

    if (!is_null($criteria->phone))
      $stmt->bindValue(":p", '%' . $criteria->phone . '%');

    if (!is_null($criteria->date))
      $stmt->bindValue(":d", $criteria->date);
  }

  protected function fromAssocArray(array $a): IEntity
  {
    return new Appointment(
      $a['folio'],
      $a['phone'],
      $a['appointment_date'],
      $a['email']
    );
  }
}
