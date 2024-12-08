<?php

require_once(dirname(__FILE__) . "/Entity.php");

class User implements Entity
{
    private int $id;
    public string $usr;
    public string $pwd;
    public string $type;

    public function __construct(int $id, string $usr, string $pwd, string $type)
    {
        $this->id = $id;
        $this->usr = $usr;
        $this->pwd = $pwd;
        $this->type = $type;
    }

    public static function fromAssocArray(array $array): User
    {
        return new User(
            $array["id"],
            $array["usr"],
            $array["pwd"],
            $array["type"]
        );
    }

    public function getId(): int|string
    {
        return $this->id;
    }
}
