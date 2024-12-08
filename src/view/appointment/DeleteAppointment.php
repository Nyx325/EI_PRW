<?php
require_once '../controller/SessionController.php';
require_once '../model/repository/AppointmentRepository.php';

// Verificar si el usuario está logueado
$sessionController = new SessionController();
if ($sessionController->isLogged() == false) {
    header('Location: ../view/login.php');
    exit;
}

// Verificar si se proporcionó un ID en la URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: ../view/AppointmentMainView.php?error=invalid_id');
    exit;
}

$id = intval($_GET['id']);

// Llamar al repositorio para eliminar la cita
$appointRepo = new AppointmentRepository();
$appointRepo->delete($id);

// Redirigir de vuelta a la vista principal con un mensaje de éxito
header('Location: ../view/AppointmentMainView.php?success=deleted');
exit;
