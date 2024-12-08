<?php 
require_once(dirname(__FILE__) . "/connector.php");
require_once(dirname(__FILE__) . "/Repository.php");
require_once(dirname(__FILE__) . "/../entity/Appointment.php");

class AppointmentRepository implements Repository {
    private readonly Connector $connector;

    public function __construct(){
        $this->connector = Connector::getInstance();
    }

    public function add(Entity $entity)
    {
        if(!($entity instanceof Appointment))
            throw new Exception("\$entity debe ser una cita");

        $query = "INSERT INTO Appointment (appointment_date, user) VALUES (:date, :price)";
        $conn = $this->connector->getConnection();
        $stmt = $conn->prepare($query);
        
        $stmt->bindParam(':date', $entity->date);
        $stmt->bindParam(':price', $entity->user);

        $stmt->execute();
    }

    public function delete(int|string $id){
        $query = "DELETE FROM Appointment WHERE id = :id";
        $conn = $this->connector->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function get(int|string $id): ?Entity
    {
        $query = "SELECT * FROM Appointment WHERE id = :id";
        $connection = $this->connector->getConnection();
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $result = $stmt->fetch();
        if ($result) {
            return Appointment::fromAssocArray($result);
        }

        return null;
    }

    public function getAll(): array
    {
        $query = "SELECT * FROM Appointment";
        $connection = $this->connector->getConnection();
        $stmt = $connection->prepare($query);
        $stmt->execute();

        $result = $stmt->fetchAll();
        $services = [];

        foreach ($result as $row) {
            $services[] = Appointment::fromAssocArray($row);
        }

        return $services;
    }

    public function update(Entity $data)
    {
        if (!($data instanceof Appointment)) {
            throw new Exception("\$data debe ser un appointment");
        }

        $query = "UPDATE Appointment SET appointment_date = :date, user = :user WHERE id = :id";
        $connection = $this->connector->getConnection();
        $stmt = $connection->prepare($query);

        $stmt->bindParam(':date', $data->date);
        $stmt->bindParam(':user', $data->user);
        $stmt->bindParam(':id', $data->getId());

        $stmt->execute();
    }
}
