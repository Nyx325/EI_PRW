<?php
class Schedule implements Entity
{
  public int $id;
  public string $week_day;
  public DateTime $startHour;
  public DateTime $endHour;

  public function __construct(int $id, string $week_day, DateTime $start, DateTime $end)
  {
    $this->id = $id;
    $this->week_day = $week_day;
    $this->startHour = $start;
    $this->endHour = $end;
  }

  public static function  fromAssocArray(array $array): Schedule
  {
    return new Schedule(
      $array["id"],
      $array["day_of_week"],
      $array["start_time"],
      $array["end_time"]
    );
  }

  public function getId(): string|int
  {
    return $this->id;
  }
}
