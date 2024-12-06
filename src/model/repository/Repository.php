<?php
require_once(dirname(__FILE__) ."/../entity/Entity.php");

interface Repository {
    public function add(Entity $entity);
    public function get(string | int $id);
    public function update(Entity $data);
    public function delete(string | int $id);
    public function getAll(): array;
}