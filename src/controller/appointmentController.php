<?php
require_once("controller.php");
require_once("../model/repository/appointmentRepository.php");
require_once("../model/repository/userRepository.php");
require_once("../model/entity/exception.php");
require_once("../model/entity/user.php");

class AppointmentController extends Controller
{
  private readonly IRepository $userRepo;
  public function __construct()
  {
    parent::__construct(new AppointmentRepository());
    $this->userRepo = new UserRepository();
  }

  private function isEmailValid(string $e, ?array $msgs = null): bool
  {
    $regex = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
    $valid = preg_match($regex, $e);
    if (!is_null($msgs) && !$valid)
      $msg[] = "formato de email inválido, se espera: usuario@dominio";

    return $valid;
  }

  private function isDatetimeValid(string $str, ?array &$msgs = null): bool
  {
    $regex = '/^(19|20)\d{2}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])\s([01]\d|2[0-3]):([0-5]\d):([0-5]\d)$/';
    $fmt = preg_match($regex, $str);
    $minDate = false;

    if (!is_null($msgs) && !$fmt) {
      $msgs[] = "Formato de fecha inválido, se espera: YYYY-MM-DD HH:MM:SS";
    }

    if ($fmt) {
      $currDate = new DateTime();
      $appointmentDate = new DateTime($str);
      $minDate = $appointmentDate >= $currDate;

      if (!is_null($msgs) && !$minDate) {
        $msgs[] = "La fecha de la cita no puede ser anterior al día actual";
      }
    }

    return $fmt && $minDate;
  }

  private function isPhoneValid(string $str, ?array $msgs = null): bool
  {
    $regex = '/^\+?[0-9]{1,4}?[-. \s]?(\(?\d{1,3}?\)?[-. \s]?)?\d{1,4}[-. \s]?\d{1,4}[-. \s]?\d{1,9}$/';
    $valid = preg_match($regex, $str);
    if (!is_null($msgs) && !$valid)
      $msg[] = "formato de telefono inválido, se espera: +1 (123) 456-7890, 123-456-7890 o 1234567890";

    return $valid;
  }

  private function itemExists(int $id, ?array $msgs = null)
  {
    $exists = !is_null($this->repo->get($id));
    if (!is_null($msgs) && !$exists)
      $msg[] = "registro no encontrado";

    return  $exists;
  }

  protected function validateAdd(IEntity $data)
  {
    if (!$data instanceof Appointment)
      throw new Exception("\$data debe ser de tipo Appointment");

    $msg = [];

    $this->isEmailValid($data->email, $msg);

    if (is_null($this->userRepo->get($data->email)))
      $msg[] = "usuario no encontrado";

    $this->isDatetimeValid($data->date, $msg);
    $this->isPhoneValid($data->phone, $msg);


    if (count($msg) > 0)
      throw new UserVisibleException(implode(", ", $msg));
  }

  protected function validateUpdate(IEntity $data)
  {
    if (!$data instanceof Appointment)
      throw new Exception("\$data debe ser de tipo Appointment");

    $msg = [];

    $this->itemExists($data->folio, $msg);
    $this->isEmailValid($data->email, $msg);
    $this->isDatetimeValid($data->date, $msg);
    $this->isPhoneValid($data->phone, $msg);


    if (count($msg) > 0)
      throw new UserVisibleException(implode(", ", $msg));
  }

  protected function validateDelete(int|string $id)
  {
    $msg = [];
    $this->itemExists($id, $msg);
    if (count($msg) > 0)
      throw new UserVisibleException($msg[0]);
  }

  protected function validateSearch(IEntityCriteria $criteria)
  {
    if (!$criteria instanceof AppointmentCriteria)
      throw new Exception("\$criteria debe ser de tipo AppointmentCriteria");
  }
}
