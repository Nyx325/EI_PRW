<?php
require_once("entity.php");

class User implements IEntity
{
  public readonly string $email;
  public string $pwd;
  public string $type;

  public function __construct(string $email, string $pwd, string $type)
  {
    $this->email = $email;
    $this->pwd = $pwd;
    $this->type = $type;
  }

  public function getId(): int|string
  {
    return $this->email;
  }
}

class UserCriteria implements IEntityCriteria
{
  public ?string $email;
  public ?string $type;

  public function __construct(?string $email, ?string $type)
  {
    $this->email = $email;
    $this->type = $type;
  }

  public function toAssocArray(): array
  {
    $assocArray = [];

    if ($this->email !== null) {
      $assocArray['email'] = $this->email;
    }

    if ($this->type !== null) {
      $assocArray['type'] = $this->type;
    }

    return $assocArray;
  }
}
