<?php
require_once "Parameters.php";

class Db {
    private $host;
    private $db;
    private $user;
    private $pass;
    private $connection;

    public function __construct($host_name, $db_name, $db_user, $db_pass) {
        $this->host = $host_name;
        $this->db = $db_name;
        $this->user = $db_user;
        $this->pass = $db_pass;

        try {
            $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db . ';charset=utf8mb4';
            $this->connection = new PDO($dsn, $this->user, $this->pass);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->connection;
    }
}
?>

