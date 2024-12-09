<?php
require_once("../controller/SessionController.php");
$sessionCtrl = new SessionController();

if ($sessionCtrl->isLogged()) {
    header('Location: ../../index.php');
    exit;
}
?>
<?php
include("./header.php");
?>
<main class="m-3">
    <section class="border m-1">
        <form action="" method="post" class="m-3">
            <legend>Iniciar sesión</legend>
            <div class="mb-3">
                <label class="form-label" for="">Usuario</label>
                <input class="form-control" type="text">
            </div>
            <div class="mb-3">
                <label class="form-label" for="">Contraseña</label>
                <input class="form-control" type="password">
            </div>
            <button class="btn btn-primary">Iniciar sesión</button>
        </form>
    </section>
</main>
<?php
include("./footer.php");
?>