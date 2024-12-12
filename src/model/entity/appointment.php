<?php
require_once("entity.php");

class Appointment implements IEntity
{
  public readonly int $folio;
  public string $phone;
  public string $date;
  public string $email;

  public function __construct(int $folio, string $phone, string $date, string $email)
  {
    $this->folio = $folio;
    $this->phone = $phone;
    $this->date = $date;
    $this->email = $email;
  }

  public function getId(): int|string
  {
    return $this->folio;
  }
}

class AppointmentCriteria implements IEntityCriteria
{
  public ?string $phone;
  public ?string $date;
  public ?string $email;

  public function __construct(?string $phone = null, ?string $date = null, ?string $email = null)
  {
    $this->phone = $phone;
    $this->date = $date;
    $this->email = $email;
  }
}
