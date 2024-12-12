<?php
require_once("entity.php");

class AppointmentDetail implements IEntity
{
  public int $folio;
  public int $serviceId;

  public function __construct(int $folio, int $serviceId)
  {
    $this->folio = $folio;
    $this->serviceId = $serviceId;
  }

  public function getId(): int|string
  {
    return  0;
  }
}
