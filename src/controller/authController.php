<?php
require_once("../model/repository/userRepository.php");
require_once("../model/entity/user.php");

require_once("../model/entity/exception.php");

class AuthController
{
  private readonly IUserRepository $repo;

  public function __construct()
  {
    $this->repo = new UserRepository();
  }

  protected function start()
  {
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
  }

  public function auth(string $email, string $pwd): bool
  {
    $usr = $this->repo->find($email, $pwd);
    if (is_null($usr)) return false;

    $this->start();
    $_SESSION['email'] = $usr->email;
    $_SESSION['type'] = $usr->type;

    return true;
  }

  public function isLogged(): ?User
  {
    $this->start();
    if (!isset($_SESSION['email'], $_SESSION['type'])) {
      session_destroy();
      return null;
    }

    return new User($_SESSION['email'], "", $_SESSION['type']);
  }

  public function logOut(): void
  {
    if (!isset($_SESSION['email'], $_SESSION['type'])) {
      unset($_SESSION['email']);
      unset($_SESSION['type']);
    }
  }
}
