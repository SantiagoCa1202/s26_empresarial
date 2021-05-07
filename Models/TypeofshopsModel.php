<?php

class TypeofshopsModel extends Mysql
{
  public $id;
  public $type_trade;
  public function __construct()
  {
    parent::__construct();
  }

  public function selectTypetrade(int $id)
  {
    $this->id = $id;
    $sql = "SELECT * FROM type_of_shops WHERE id = $this->id";
    $request = $this->select($sql);
    return $request;
  }
}
