<?php
require_once("../model/entity/appointmentDetail.php");
require_once("routes.php");
require_once("../controller/appointmentDetailController.php");

class AppointmentDetailRoutes extends Routes
{
  public function __construct()
  {
    parent::__construct(new AppointmentDetailController(), "id", "Detalle de cita");
  }

  protected function addDataFromBody($r): IEntity
  {
    if (!isset($r['folio'], $r['service_id']))
      throw new UserVisibleException("Se deben definir 'folio', y 'service_id'");

    return new AppointmentDetail(
      0,
      $r['folio'],
      $r['service_id']
    );
  }

  protected function updateDataFromBody($r): IEntity
  {
    if (!isset($r['id'], $r['folio'], $r['service_id']))
      throw new UserVisibleException("Se deben definir 'id', 'folio', y 'service_id'");

    return new AppointmentDetail(
      $r['id'],
      $r['folio'],
      $r['service_id']
    );
  }

  protected function criteriaFromParams($r): IEntityCriteria
  {
    return new AppointmentDetailCriteria(
      isset($r['folio']) ? $r['folio'] : null,
      isset($r['service']) ? $r['service'] : null,
    );
  }
}

$routes = new AppointmentDetailRoutes();
$routes->handleRequest();
