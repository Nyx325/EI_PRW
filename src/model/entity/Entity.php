<?php
interface Entity {
    public function getId(): string | int;
    public static function fromAssocArray(array $array): Entity;
}
