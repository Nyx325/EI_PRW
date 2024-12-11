<?php
require("controller.php");
require("../model/repository/userRepository.php");
require("../model/entity/exception.php");

class UserController extends Controller
{
  public function __construct()
  {
    parent::__construct(new UserRepository());
  }

  protected function validateAdd(IEntity $data)
  {
    if (!$data instanceof User)
      throw new UserVisibleException("\$data debe ser de tipo User");

    $msg = [];
    if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $data->email))
      $msg[] = "formato email inválido se espera: usuario@dominio";

    if (!preg_match('/^(ADMIN|CLIENT)$/', $data->type))
      $msg[] = "tipo usuario inválido se espera: CLIENT o ADMIN";

    if (count($msg) > 0)
      throw new UserVisibleException(implode(", ", $msg));
  }

  protected function validateUpdate(IEntity $data)
  {
    if (!$data instanceof User)
      throw new UserVisibleException("\$data debe ser de tipo User");

    if (is_null($this->repo->get($data->email)))
      throw new UserVisibleException("No se encontró el usuario");

    if (!preg_match('/^(ADMIN|CLIENT)$/', $data->type))
      throw new UserVisibleException("tipo usuario inválido se espera: CLIENT o ADMIN");
  }

  protected function validateDelete(int|string $id)
  {
    if (is_null($this->repo->get($id)))
      throw new UserVisibleException("Usuario no encontrado");
  }

  protected function validateSearch(IEntityCriteria $criteria)
  {
    if (!$criteria instanceof UserCriteria)
      throw new UserVisibleException("\$criteria debe ser de tipo UserCriteria");
  }
}
