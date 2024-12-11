<?php
require_once("../model/entity/service.php");
require_once("routes.php");
require_once("../controller/serviceController.php");

class ServiceRoutes extends Routes
{
  public function __construct()
  {
    parent::__construct(new ServiceController(), "id", "Servicio");
  }

  protected function addDataFromBody($r): IEntity
  {
    if (!isset($r['description'], $r['price']))
      throw new UserVisibleException("Se deben definir 'description' y 'price'");

    return new Service(0, $r['description'], $r['price']);
  }

  protected function updateDataFromBody($r): IEntity
  {
    if (!isset($r['id'], $r['description'], $r['price']))
      throw new UserVisibleException("Se deben definir 'id', 'description' y 'price'");


    return new Service($r['id'], $r['description'], $r['price']);
  }

  protected function criteriaFromParams($r): IEntityCriteria
  {
    $description = isset($r['description']) ? $r['description'] : null;
    $price = isset($r['price']) ? $r['price'] : null;
    return new ServiceCriteria($description, $price, null, null);
  }
}

$routes = new ServiceRoutes();
$routes->handleRequest();
