<?php
require_once("controller.php");
require_once("../model/repository/serviceRepository.php");
require_once("../model/entity/exception.php");

class ServiceController extends Controller
{
  public function __construct()
  {
    parent::__construct(new ServiceRepository());
  }

  protected function validateAdd(IEntity $data)
  {
    if (!$data instanceof Service)
      throw new UserVisibleException("\$data debe ser de tipo Service");

    $msg = [];
    if (!preg_match("^[A-Za-zÁáÉéÍíÓóÚúÑñ\s\.,;:!?()\-]{5,100}$", $data->description))
      $msg[] = "formato de descripción inválido";

    if (!preg_match("^\d+(\.\d{1,2})?$", $data->price))
      $msg[] = "formato de precio inválido se espera: 350.04";

    if (count($msg) > 0)
      throw new UserVisibleException(implode(", ", $msg));
  }

  protected function validateUpdate(IEntity $data) {}

  protected function validateDelete(int|string $id) {}

  protected function validateSearch(IEntityCriteria $criteria) {}
}
