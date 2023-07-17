<?php

class Database
{
  private $connection;
  private static $_instance;
  private $host = DB_HOST;
  private $username = DB_USER;
  private $password = DB_PASSWORD;
  private $db_name = DB_DATABASE;
  private $charset = DB_CHARSET;
  /*
    Get an instance of the Database
    @return Instance
    */
  public static function getInstance()
  {
    if (!self::$_instance) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }
  // Constructor
  private function __construct()
  {
    try {
      $this->connection = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
      $this->connection->exec($this->charset);
      $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      // Error handling
    } catch (PDOException $e) {
      die("Failed to connect to DB: " . $e->getMessage());
    }
  }
  // Magic method clone is empty to prevent duplication of connection
  private function __clone()
  { }

  // Get the connection	
  public function dbConnection()
  {
    return $this->connection;
  }

  public function DB(){
    try {
      $this->connection = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
      $this->connection->exec($this->charset);
      $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $this->connection;
      // Error handling
    } catch (PDOException $e) {
      die("Failed to connect to DB: " . $e->getMessage());
    }
  }
}
?>