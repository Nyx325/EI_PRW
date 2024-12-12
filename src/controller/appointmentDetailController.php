<?php
require_once("controller.php");
require_once("../model/entity/exception.php");

require_once("../model/repository/appointmentRepository.php");
require_once("../model/entity/appointment.php");

require_once("../model/repository/appointmentDetailRepository.php");
require_once("../model/entity/appointmentDetail.php");

require_once("../model/repository/serviceRepository.php");
require_once("../model/entity/service.php");

class AppointmentDetailController extends Controller
{
  private readonly IRepository $appointmentRepo;
  private readonly IRepository $serviceRepo;

  public function __construct()
  {
    parent::__construct(new AppointmentDetailRepository());
    $this->appointmentRepo = new AppointmentRepository();
    $this->serviceRepo = new ServiceRepository();
  }

  protected function validateAdd(IEntity $data)
  {
    if (!$data instanceof AppointmentDetail)
      throw new Exception("\$data debe ser de tipo AppointmentDetail");

    $msg = [];
    if (is_null($this->appointmentRepo->get($data->folio)))
      $msg[] = "no se encontró la cita para el detalle";

    if (is_null($this->serviceRepo->get($data->serviceId)))
      $msg[] = "no se encontró el servicio para el detalle";

    if (count($msg) > 0)
      throw new UserVisibleException(implode(", ", $msg));
  }

  protected function validateDelete(int|string $id)
  {
    if (is_null($this->repo->get($id)))
      throw new UserVisibleException("Registro no encontrado");
  }

  protected function validateUpdate(IEntity $data)
  {
    if (!$data instanceof AppointmentDetail)
      throw new UserVisibleException("\$data debe ser de tipo AppointmentDetail");

    if (is_null($this->repo->get($data->id)))
      throw new UserVisibleException("Registro no encontrado");
  }

  protected function validateSearch(IEntityCriteria $criteria)
  {
    if (!$criteria instanceof AppointmentDetailCriteria)
      throw new Exception("\$criteria debe ser de tipo AppointmentDetailCriteria");
  }
}
