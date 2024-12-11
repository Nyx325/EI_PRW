<?php
require_once("../model/entity/user.php");
require_once("routes.php");
require_once("../controller/userController.php");

class UserRoutes extends Routes
{
  public function __construct()
  {
    parent::__construct(new UserController(), "email", "Usuario");
  }

  protected function addDataFromBody($req): IEntity
  {
    if (!isset($req['email'], $req['pwd'], $req['type']))
      throw new UserVisibleException("Se deben definir 'email', 'pwd' y 'type'");

    return new User($req['email'], $req['pwd'], $req['type']);
  }

  protected function updateDataFromBody($reqBody): IEntity
  {
    if (!isset($req['email'], $req['pwd'], $req['type']))
      throw new UserVisibleException("Se deben definir 'email', 'pwd' y 'type'");

    return new User($req['email'], $req['pwd'], $req['type']);
  }

  protected function criteriaFromParams($req): IEntityCriteria
  {
    $email = isset($req['email']) ? $req['email'] : null;
    $type = isset($req['type']) ? $req['type'] : null;
    return new UserCriteria($email, $type);
  }
}

$routes = new UserRoutes();
$routes->handleRequest();
