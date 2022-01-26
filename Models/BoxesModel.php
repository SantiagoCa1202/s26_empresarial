<?php

class BoxesModel extends Mysql
{
  public $id;

  public $db_company;

  public function __construct()
  {
    parent::__construct();
  }

  public function selectBox(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $sql = "SELECT * FROM boxes WHERE id = $this->id";
    $request = $this->select_company($sql, $this->db_company);

    return $request;
  }

}
