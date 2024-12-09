<?php
require_once("./src/controller/SessionController.php");
$sessionCtrl = new SessionController();

if (!($sessionCtrl->isLogged())) {
    header('Location: src/view/LoginView.php');
    exit;
}
?>
<?php
include("src/view/header.php");
?>
<main>
    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Tenetur laudantium vitae soluta, consequuntur, cumque maxime pariatur similique accusamus dolorem, fugit eaque corrupti beatae. Dolores magnam consequuntur libero est quam nam.</p>
</main>
<?php
include("src/view/footer.php");
?>