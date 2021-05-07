<?php
  class Connection{
    
    private $connect;

    public function __construct(string $db = DB_NAME ) {
      $connectionString = "mysql:hos=".DB_HOST.";dbname=".$db.";.DB_CHARSET.";
      try {
        $this->connect = new PDO($connectionString, DB_USER, DB_PASSWORD);
        $this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (Exception $e) {
        $this->connect = 'Error de conexión';
        echo "ERROR: ". $e->getMessage();
      }
    }

    public function connect() {
      return $this->connect;
    }
  }
?>