<?php
class Schedule implements Entity
{
  private int $id;
  public string $week_day;
  public string $start;
  public string $end;

  public function __construct(int $id, string $week_day, string $start, string $end)
  {
    $this->id = $id;
    $this->week_day = $week_day;
    $this->start = $start;
    $this->end = $end;
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
