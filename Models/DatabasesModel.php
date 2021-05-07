<?php

class DatabasesModel extends Mysql
{
  public $id;
  public $data_base;
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
}
