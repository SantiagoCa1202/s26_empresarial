<?php

class SystemModel extends Mysql
{
  public $id;
  public $data_base;
  public $type_trade;
  
  public function __construct()
  {
    parent::__construct();
  }

  public function selectDatabase(int $id)
  {
    $this->id = $id;
    $sql = "SELECT * FROM data_bases WHERE id = $this->id";
    $request = $this->select($sql);
    return $request;
  }

  public function selectTypetrade(int $id)
  {
    $this->id = $id;
    $sql = "SELECT * FROM type_of_shops WHERE id = $this->id";
    $request = $this->select($sql);
    return $request;
  }

  public function selectPaymentMethod(int $id)
  {
    $this->id = $id;
    $sql = "SELECT * FROM payment_methods WHERE id = $this->id";
    $request = $this->select($sql);
    return $request;
  }
}
