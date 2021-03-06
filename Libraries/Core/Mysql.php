<?php
class Mysql extends Connection
{
  private object $connection;
  private object $connection_company;
  private $strQuery;
  private $arrValues;

  function __construct()
  {
    $this->connection = new Connection();
    $this->connection_system = $this->connection->connect();
  }

  public function insert(string $query, array $arrValues)
  {
    $this->strQuery = $query;
    $this->arrValues = $arrValues;

    $insert = $this->connection_system->prepare($this->strQuery);
    $resInsert = $insert->execute($this->arrValues);

    if ($resInsert) {
      $lastInsert = $this->connection_system->lastInsertId();
    } else {
      $lastInsert = 0;
    }
    return $lastInsert;
  }

  public function select(string $query)
  {
    $this->strQuery = $query;

    $result = $this->connection_system->prepare($this->strQuery);
    $result->execute();
    $data = $result->fetch(PDO::FETCH_ASSOC);
    return $data;
  }

  public function select_all(string $query)
  {
    $this->strQuery = $query;

    $result = $this->connection_system->prepare($this->strQuery);
    $result->execute();
    $data = $result->fetchall(PDO::FETCH_ASSOC);
    return $data;
  }

  public function select_dates(string $column, string $table)
  {
    $query = "SELECT DISTINCTROW DATE($column) as day FROM $table";
    $this->strQuery = $query;

    $days = $this->connection_system->prepare($this->strQuery);
    $days->execute();

    $query = "SELECT DISTINCTROW DATE_FORMAT($column,'%Y-%m') as month FROM $table";
    $this->strQuery = $query;

    $months = $this->connection_system->prepare($this->strQuery);
    $months->execute();

    return [
      'days' => $days->fetchall(PDO::FETCH_COLUMN),
      'months' => $months->fetchall(PDO::FETCH_COLUMN),
    ];
  }


  public function info_table(string $query)
  {
    $this->strQuery = $query;

    $result = $this->connection_system->prepare($this->strQuery);
    $result->execute();
    $data = $result->fetch(PDO::FETCH_ASSOC);
    return $data;
  }

  public function update(string $query, array $arrValues)
  {
    $this->strQuery = $query;
    $this->arrValues = $arrValues;

    $update = $this->connection_system->prepare($this->strQuery);
    $resExecute = $update->execute($this->arrValues);
    return $resExecute;
  }

  public function delete(string $query)
  {
    $this->strQuery = $query;
    $result = $this->connection_system->prepare($this->strQuery);
    $del = $result->execute();
    return $del;
  }

  public function insert_company(string $query, array $arrValues, string $db_company)
  {
    $this->connection_company = $this->connection->connect_company($db_company);

    $this->strQuery = $query;
    $this->arrValues = $arrValues;

    $insert = $this->connection_company->prepare($this->strQuery);
    $resInsert = $insert->execute($this->arrValues);

    if ($resInsert) {
      $lastInsert = $this->connection_company->lastInsertId();
    } else {
      $lastInsert = 0;
    }
    return $lastInsert;
  }

  public function select_company(string $query, string $db_company)
  {
    $this->connection_company = $this->connection->connect_company($db_company);

    $this->strQuery = $query;

    $result = $this->connection_company->prepare($this->strQuery);
    $result->execute();
    $data = $result->fetch(PDO::FETCH_ASSOC);
    return $data;
  }

  public function select_all_company(string $query, string $db_company, $type = 1)
  {
    $this->connection_company = $this->connection->connect_company($db_company);

    $this->strQuery = $query;

    $result = $this->connection_company->prepare($this->strQuery);
    $result->execute();
    $data = $type == 1 ? $result->fetchall(PDO::FETCH_ASSOC) : $result->fetchall(PDO::FETCH_COLUMN);
    return $data;
  }

  public function select_dates_company(string $column, string $table, string $db_company)
  {
    $this->connection_company = $this->connection->connect_company($db_company);

    $query = "SELECT DISTINCTROW DATE($column) as day FROM $table";
    $this->strQuery = $query;

    $days = $this->connection_company->prepare($this->strQuery);
    $days->execute();

    $query = "SELECT DISTINCTROW DATE_FORMAT($column,'%Y-%m') as month FROM $table";
    $this->strQuery = $query;

    $months = $this->connection_company->prepare($this->strQuery);
    $months->execute();

    return [
      'days' => $days->fetchall(PDO::FETCH_COLUMN),
      'months' => $months->fetchall(PDO::FETCH_COLUMN),
    ];
  }


  public function info_table_company(string $query, string $db_company)
  {
    $this->connection_company = $this->connection->connect_company($db_company);

    $this->strQuery = $query;

    $result = $this->connection_company->prepare($this->strQuery);
    $result->execute();
    $data = $result->fetch(PDO::FETCH_ASSOC);
    return $data;
  }

  public function update_company(string $query, array $arrValues, string $db_company)
  {
    $this->connection_company = $this->connection->connect_company($db_company);

    $this->strQuery = $query;
    $this->arrValues = $arrValues;

    $update = $this->connection_company->prepare($this->strQuery);
    $resExecute = $update->execute($this->arrValues);
    return $resExecute;
  }

  public function delete_company(string $query, string $db_company)
  {
    $this->connection_company = $this->connection->connect_company($db_company);

    $this->strQuery = $query;
    $result = $this->connection_company->prepare($this->strQuery);
    $del = $result->execute();
    return $del;
  }
}
