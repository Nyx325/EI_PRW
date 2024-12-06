<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Hello world</h1>

    <?php
    require_once(dirname(__FILE__) . "/src/model/repository/ServiceRepository.php");
    $repo = new ServiceRepository();
    $user = new Service(0, "peinado de ceja", 1.2);

    try {
        //$repo->add($user);
        $users = $repo->getAll();
        foreach ($users as $u) {
            if ($u instanceof Service) {
                echo $u->description . " " . $u->price . "<br>";
            }
        }
    } catch (Exception $e) {
        echo "Error";
        echo $e->getMessage();
    }
