<?php
class Connector
{
  private $pdo;
  private static $instance;

  private function __construct()
  {
    $config = require_once(dirname(__FILE__) . "/../../../config.php");

    $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
    try {
      $this->pdo = new PDO($dsn, $config['user'], $config['password']);
      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      die("Error de conexión: " . $e->getMessage());
    }
  }

  public static function getInstance(): self
  {
    if (!self::$instance) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  public function getConnection(): PDO
  {
    return $this->pdo;
  }
}
