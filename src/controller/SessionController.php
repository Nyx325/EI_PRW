<?php
require("../model/repository/UserRepository.php");

 class SessionController
{
  private readonly UserRepository $repo;

  public function __construct()
  {
    $this->repo = new UserRepository();
  }

  public function isLogged(): ?User
  {
    session_start();

    if (!isset($_SESSION['id'], $_SESSION['usr'], $_SESSION['type'])){
      $this->logOut();
      session_destroy();
      return null;
    }

    return new User($_SESSION['id'], $_SESSION['usr'], "-", $_SESSION['type']);
  }

  public function logIn(string $usr, string $pwd): bool
  {
    $usr = $this->repo->getUser($usr, $pwd);

    if ($usr === null) return false;

    session_start();

    $_SESSION['id'] = $usr->getId();
    $_SESSION['usr'] = $usr->usr;
    $_SESSION['type'] = $usr->type;

    return true;
  }

  public function logOut()
  {
    session_start();
    session_destroy();
  }
}
