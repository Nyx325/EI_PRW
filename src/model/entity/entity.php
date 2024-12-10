<?php
interface IEntity {
    public function getId(): int | string;
    public static function fromAssocArray(array $arr): IEntity;
}

interface IEntityCriteria {
    public function toAssocArray(): array;
}