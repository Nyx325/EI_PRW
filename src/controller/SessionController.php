<?php
abstract class SessionController
{
    private readonly UserRepository $repo;

    public function isLogged(): User
    {
        session_start();

        if (is_null($_SESSION['id']) || is_null($_SESSION['usr']) || is_null($_SESSION['type']))
            return null;

        return new User($_SESSION['id'], $_SESSION['usr'], "", $_SESSION['type']);
    }

    public function sigIn(string $usr, string $pwd): bool
    {
        $usr = $this->repo->getUser($usr, $pwd);

        if($usr === null) return false;

        session_start();

        $_SESSION['id'] = $usr->getId();
        $_SESSION['usr'] = $usr->usr;
        $_SESSION['type'] = $usr->type;

        return true;
    }

    public function logOut(){
        session_destroy();
    }
}
