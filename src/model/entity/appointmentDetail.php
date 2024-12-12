<?php
require_once("entity.php");

class AppointmentDetail implements IEntity
{
  public readonly int $id;
  public int $folio;
  public int $serviceId;

  public function __construct(int $id, int $folio, int $serviceId)
  {
    $this->id = $id;
    $this->folio = $folio;
    $this->serviceId = $serviceId;
  }

  public function getId(): int|string
  {
    return $this->id;
  }
}

class AppointmentDetailCriteria implements IEntityCriteria
{
  public ?int $folio;
  public ?int $serviceId;

  public function __construct(?int $folio = null, ?int $serviceId = null)
  {
    $this->folio = $folio;
    $this->serviceId = $serviceId;
  }
}
