<?php

require_once(dirname(__FILE__) . "/Entity.php");

class Appointment implements Entity
{
  public int $id;
  public DateTime $date;
  public int $user;

  public function __construct(int $id, DateTime $date, float $user)
  {
    $this->id = $id;
    $this->date = $date;
    $this->user = $user;
  }

  public static function fromAssocArray(array $array): Appointment
  {
    return new Appointment(
      $array["id"],
      new DateTime($array["appointment_date"]),
      $array["user"]
    );
  }

  public function getId(): int|string
  {
    return $this->id;
  }
}
