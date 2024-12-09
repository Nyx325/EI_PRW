<?php
require_once '../controller/SessionController.php';
require_once '../model/repository/AppointmentRepository.php';
require_once '../../controller/SessionController.php';

$sessionController = new SessionController();
if (is_null($sessionController->isLogged())) {
  header('Location: ../login.php');
  exit;
}

if (!(isset($_GET['id']))){
    header('Location: AppointmentMainView.php');
    exit;
}

$appointmentRepo = new AppointmentRepository();
$appointment = $appointmentRepo->get($_GET['id']);

if(is_null($appointment) || !($appointment instanceof Appointment)){
    header('Location: AppointmentMainView.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post">
        <div>
            <label for="">Fecha</label>
            <input type="datetime" value="<?php $appointment->date ?>">
        </div>
    </form>
</body>
</html>