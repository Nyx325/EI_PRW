<?php
class Appointment implements IEntity
{
  private readonly int $folio;
  private string $phone;
  private string $date;
  private string $email;

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
  private ?string $phone;
  private ?string $date;
  private ?string $email;

  public function __construct(?string $phone = null, ?string $date = null, ?string $email = null)
  {
    $this->phone = $phone;
    $this->date = $date;
    $this->email = $email;
  }
}
