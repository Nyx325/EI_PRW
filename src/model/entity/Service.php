<?php

require_once(dirname(__FILE__) . "/Entity.php");

class Service implements Entity
{
    private int $id;
    public string $description;
    public float $price;

    public function __construct(int $id, string $description, float $price)
    {
        $this->id = $id;
        $this->description = $description;
        $this->price = $price;
    }

    public static function fromAssocArray(array $array): Service
    {
        return new Service(
            $array["id"],
            $array["description"],
            $array["price"]
        );
    }

    public function getId(): int|string
    {
        return $this->id;
    }
}
