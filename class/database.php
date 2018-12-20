<?php
/**
 * Created by PhpStorm.
 * User: Ksar
 * Date: 19.12.2018
 * Time: 17:14
 */

class Database {

    private $host;
    private $db_name;
    private $username;
    private $password;
    public $connection;

    function __construct () {
        $database_config = new DatabaseConfig();
        $this->host = $database_config->host;
        $this->db_name = $database_config->db_name;
        $this->username = $database_config->username;
        $this->password = $database_config->password;
    }

    public function getConnection() {
        $this->connection = null;

        try {
            $this->connection = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ';charset=utf8mb4', $this->username, $this->password);
            $this->connection->exec("SET NAMES utf8");
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->connection;
    }
}
