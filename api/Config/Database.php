<?php

class Database {

  // specify your own database credentials
  private $host = "185.115.218.166";

  private $db_name = "fs_thomasp";

  private $username = "fs_thomasp";

  private $password = "8QejcPCIvbTZ";

  public $conn;

  // get the database connection
  public function getConnection() {
    $this->conn = NULL;
    try {
      $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
      $this->conn->exec("set names utf8");
    } catch (PDOException $exception) {
      echo "Connection error: " . $exception->getMessage();
    }
    return $this->conn;
  }

}
