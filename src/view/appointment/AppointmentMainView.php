<?php
require_once '../../controller/SessionController.php';
$sessionController = new SessionController();
if (is_null($sessionController->isLogged())) {
  header('Location: ../login.php');
  exit;
}

$appointRepo = new AppointmentRepository();
$userRepo = new UserRepository();
$appointments = $appointRepo->getAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cita</title>
</head>

<body>
  <header>
    <h1>Barbershop</h1>
  </header>
  <main>
    <nav>
      <button type="button">Volver</button>
      <button type="button">Agregar</button>
    </nav>
    <table>
      <thead>
        <tr>
          <th>Folio</th>
          <th>Fecha</th>
          <th>Usuario</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($appointments as $appointment) {
          if (!($appointment instanceof Appointment)) continue;
          $user = $userRepo->get($appointment->user);
          if (!($user instanceof User)) continue;
        ?>
          <tr>
            <td><?php $appointment->getId() ?></td>
            <td><?php $appointment->date ?></td>
            <td><?php $user->usr ?></td>
            <td>
              <a href="DeleteAppointment.php?id=<?php echo $appointment->getId(); ?>"
                onclick="return confirm('¿Estás seguro de eliminar esta cita?');">
                Eliminar
              </a>
            </td>
            <td>
              <a href="UpdateAppointment.php?id=<?php echo $appointment->getId(); ?>">
                Modificar
              </a>

            </td>

            <td><button type="button">Modificar</button></td>
          </tr>

        <?php
        }
        ?>

      </tbody>
    </table>
  </main>
  <footer>
    <p>Todos los derechos reservados</p>
  </footer>
</body>

</html>