<?php 
  class Mysql extends Connection
  {
    private object $connection;
    private $strQuery;
    private $arrValues;

    function __construct()
    {
      $this->connection = new Connection();
      $this->connection = $this->connection->connect();
    }

    public function insert(string $query, array $arrValues)
    {
      $this->strQuery = $query;
      $this->arrValues = $arrValues;

      $insert = $this->connection->prepare($this->strQuery);
      $resInsert = $insert->execute($this->arrValues);

      if($resInsert)
      {
        $lastInsert = $this->connection->lastInsertId();
      }else{
        $lastInsert = 0;
      }
      return $lastInsert;
    }

    public function select(string $query)
    {
      $this->strQuery = $query;

      $result = $this->connection->prepare($this->strQuery);
      $result->execute();
      $data = $result->fetch(PDO::FETCH_ASSOC);
      return $data;
    }

    public function select_all(string $query)
    {
      $this->strQuery = $query;

      $result = $this->connection->prepare($this->strQuery);
      $result->execute();
      $data = $result->fetchall(PDO::FETCH_ASSOC);
      return $data;
    }

    public function info_table(string $query)
    {
      $this->strQuery = $query;

      $result = $this->connection->prepare($this->strQuery);
      $result->execute();
      $data = $result->fetch(PDO::FETCH_ASSOC);
      return $data;
    }

    public function update(string $query, array $arrValues)
    {
      $this->strQuery = $query;
      $this->arrValues = $arrValues;

      $update = $this->connection->prepare($this->strQuery);
      $resExecute = $update->execute($this->arrValues);
      return $resExecute; 
    }

    public function delete(string $query)
    {
      $this->strQuery = $query;
      $result = $this->connection->prepare($this->strQuery);
      $del = $result->execute();
      return $del;
    }
  }

?>