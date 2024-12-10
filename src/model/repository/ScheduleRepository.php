<?php
require("connector.php");
require("Repository.php");
require("../entity/Appointment.php");

class ScheduleRepository implements Repository
{
    private readonly Connector $connector;

    public function __construct()
    {
        $this->connector = Connector::getInstance();
    }


    public function add(Entity $entity)
    {
        if (!($entity instanceof Appointment))
            throw new Exception("\$entity debe ser una cita");

        $query = "INSERT INTO Schedule (day_of_week, user) VALUES (:date, :price)";
        $conn = $this->connector->getConnection();
        $stmt = $conn->prepare($query);

        $stmt->bindParam(':date', $entity->date->format('Y-m-d H:i:s'));
        $stmt->bindParam(':price', $entity->user);

        $stmt->execute();
    }
}
