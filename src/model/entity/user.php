<?php
require("entity.php");

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

    public static function fromAssocArray(array $arr): IEntity
    {
        if (!isset($arr['email'], $arr['pwd'], $arr['type'])) {
            throw new Exception("Array no contiene los datos necesarios para crear un User");
        }

        return new self(
            $arr['email'],
            $arr['pwd'],
            $arr['type']
        );
    }
}

class UserCriteria implements IEntityCriteria {
    public ?string $email; 
    public ?string $pwd; 
    public ?string $type; 

    public function __construct(?string $email, ?string $pwd, ?string $type)
    {
        $this->email = $email;
        $this->pwd = $pwd;
        $this->type = $type;
    }

    public function toAssocArray(): array
    {
        $assocArray = [];

        if ($this->email !== null) {
            $assocArray['email'] = $this->email;
        }
        if ($this->pwd !== null) {
            $assocArray['pwd'] = $this->pwd;
        }
        if ($this->type !== null) {
            $assocArray['type'] = $this->type;
        }

        return $assocArray;
    }
}
