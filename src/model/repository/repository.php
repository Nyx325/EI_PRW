<?php
require("../entity/entity.php");

interface IRepository {
    public function add(IEntity $data): void;
    public function update(IEntity $data): void;
    public function delete(int | string $id): void;
    public function get(int | string $id): IEntity;
    public function getBy(?IEntityCriteria $criteria): array;
}