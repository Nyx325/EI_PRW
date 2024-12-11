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

  protected function isDescriptionValid(string $desc, ?array $msgs = null): bool
  {
    $regex = "/^[A-Za-zÁáÉéÍíÓóÚúÑñ\s\.,;:!?()\-]{5,100}$/";
    $valid = preg_match($regex, $desc);

    if (!is_null($msgs) && !$valid)
      $msg[] = "formato de descripción inválido";

    return $valid;
  }

  protected function isPriceValid(float $price, ?array $msgs = null): bool
  {
    $regex = "/^\d+(\.\d{1,2})?$/";
    $valid = preg_match($regex, $price);

    if (!is_null($msgs) && !$valid)
      $msg[] = "formato de precio inválido se espera: 350.04";

    return $valid;
  }

  protected function itemExist(int $id, ?array $msgs = null): bool
  {
    $service = $this->repo->get($id);
    $valid = !is_null($service);

    if (!is_null($msgs) && !$valid)
      $msg[] = "no se encontró el registro";

    return $valid;
  }

  protected function validateAdd(IEntity $data)
  {
    if (!$data instanceof Service)
      throw new Exception("\$data debe ser de tipo Service");

    $msg = [];
    $this->isDescriptionValid($data->description, $msg);
    $this->isPriceValid($data->price, $msg);

    if (count($msg) > 0)
      throw new UserVisibleException(implode(", ", $msg));
  }

  protected function validateUpdate(IEntity $data)
  {
    if (!$data instanceof Service)
      throw new Exception("\$data debe ser de tipo Service");

    $msg = [];
    $this->itemExist($data->id, $msg);
    $this->isDescriptionValid($data->description, $msg);
    $this->isPriceValid($data->price, $msg);

    if (count($msg) > 0)
      throw new UserVisibleException(implode(", ", $msg));
  }

  protected function validateDelete(int|string $id)
  {
    $msg = [];
    $this->itemExist($id, $msg);

    if (count($msg) > 0)
      throw new UserVisibleException(implode(", ", $msg));
  }

  protected function validateSearch(IEntityCriteria $criteria)
  {
    if (!$criteria instanceof ServiceCriteria)
      throw new Exception("\$criteria debe ser de tipo UserCriteria");
  }
}
