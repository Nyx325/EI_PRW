<?php
require_once("../model/entity/appointment.php");
require_once("routes.php");
require_once("../controller/appointmentController.php");

class AppointmentRoutes extends Routes
{
  public function __construct()
  {
    parent::__construct(new AppointmentController(), "folio", "Cita");
  }

  protected function addDataFromBody($r): IEntity
  {
    if (!isset($r['phone'], $r['date'], $r['email']))
      throw new UserVisibleException("Se deben definir 'phone', 'date' y 'email'");

    return new Appointment(
      0,
      $r['phone'],
      $r['date'],
      $r['email']
    );
  }

  protected function updateDataFromBody($r): IEntity
  {
    if (!isset($r['folio'], $r['phone'], $r['date'], $r['email']))
      throw new UserVisibleException("Se deben definir 'phone', 'date' y 'email'");

    return new Appointment(
      $r['folio'],
      $r['phone'],
      $r['date'],
      $r['email']
    );
  }

  protected function criteriaFromParams($r): IEntityCriteria
  {
    return new AppointmentCriteria(
      isset($r['phone']) ? $r['phone'] : null,
      isset($r['date']) ? $r['date'] : null,
      isset($r['email']) ? $r['email'] : null,
    );
  }
}

$routes = new AppointmentRoutes();
$routes->handleRequest();
