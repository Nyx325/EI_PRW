<?php
require_once("../model/repository/repository.php");

abstract class Controller
{
  protected readonly IRepository $repo;

  public function __construct(IRepository $repo)
  {
    $this->repo = $repo;
  }

  public function add(IEntity $data): void
  {
    $this->validateAdd($data);
    $this->repo->add($data);
  }

  public function update(IEntity $data): void
  {
    $this->validateUpdate($data);
    $this->repo->update($data);
  }

  public function get(string | int $id): ?IEntity
  {
    return $this->repo->get($id);
  }

  public function delete(int | string $id): void
  {
    $this->validateDelete($id);
    $this->repo->delete($id);
  }

  public function getBy(IEntityCriteria $criteria): array
  {
    $this->validateSearch($criteria);
    return $this->repo->getBy($criteria);
  }

  protected abstract function validateAdd(IEntity $data);
  protected abstract function validateUpdate(IEntity $data);
  protected abstract function validateDelete(int | string $id);
  protected abstract function validateSearch(IEntityCriteria $criteria);
}
