<?php
class Connection
{
  private $connect;
  private $connect_company;

  public function __construct(string $db = DB_NAME)
  {

    $connectionString = "mysql:hos=" . DB_HOST . ";dbname=" . $db . ";.DB_CHARSET.";
    try {
      $this->connect = new PDO($connectionString, DB_USER, DB_PASSWORD);
      $this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (Exception $e) {
      $this->connect = 'Error de conexión';
      echo "ERROR: " . $e->getMessage();
    }
  }

  public function connect()
  {
    return $this->connect;
  }
  public function connect_company($db_company)
  {
    $connectionString_company = "mysql:hos=" . DB_HOST . ";dbname=" . $db_company . ";.DB_CHARSET.";
    
    try {
      $this->connect_company = new PDO($connectionString_company, DB_USER, DB_PASSWORD);
      $this->connect_company->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (Exception $e) {
      $this->connect_company = 'Error de conexión';
      echo "ERROR: " . $e->getMessage();
    }
    return $this->connect_company;
  }
}
