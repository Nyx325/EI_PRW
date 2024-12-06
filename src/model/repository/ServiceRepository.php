<?php

require_once(dirname(__FILE__) . "/connector.php");
require_once(dirname(__FILE__) . "/Repository.php");
require_once(dirname(__FILE__) . "/../entity/Service.php");

class ServiceRepository implements Repository
{
    private readonly Connector $connector;

    public function __construct()
    {
        $this->connector = Connector::getInstance();
    }

    public function add(Entity $data)
    {
        if (!($data instanceof Service)) {
            throw new Exception("\$data debe ser un servicio");
        }

        $query = "INSERT INTO Services (description, price) VALUES (:description, :price)";
        $connection = $this->connector->getConnection();
        $stmt = $connection->prepare($query);

        $stmt->bindParam(':description', $data->description);
        $stmt->bindParam(':price', $data->price);

        $stmt->execute();
    }

    public function delete(int|string $id)
    {
        $query = "DELETE FROM Services WHERE id = :id";
        $connection = $this->connector->getConnection();
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function get(int|string $id): ?Entity
    {
        $query = "SELECT * FROM Services WHERE id = :id";
        $connection = $this->connector->getConnection();
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $result = $stmt->fetch();
        if ($result) {
            return Service::fromAssocArray($result);
        }

        return null;
    }

    public function getAll(): array
    {
        $query = "SELECT * FROM Services";
        $connection = $this->connector->getConnection();
        $stmt = $connection->prepare($query);
        $stmt->execute();

        $result = $stmt->fetchAll();
        $services = [];

        foreach ($result as $row) {
            $services[] = Service::fromAssocArray($row);
        }

        return $services;
    }

    public function update(Entity $data)
    {
        if (!($data instanceof Service)) {
            throw new Exception("\$data debe ser un servicio");
        }

        $query = "UPDATE Services SET description = :description, price = :price WHERE id = :id";
        $connection = $this->connector->getConnection();
        $stmt = $connection->prepare($query);

        $stmt->bindParam(':description', $data->description);
        $stmt->bindParam(':price', $data->price);
        $stmt->bindParam(':id', $data->id);

        $stmt->execute();
    }
}
