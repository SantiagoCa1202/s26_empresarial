<?php
class SalesReturnsModel extends Mysql
{
  public $id;
  public $date;
  public $status;

  public $db_company;

  public function __construct()
  {
    parent::__construct();
  }

  public function insertReturn(
    $sale_id,
    $type
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->sale_id = $sale_id;
    $this->type = $type;

    $query_insert = "INSERT INTO sales_return (sale_id, type) VALUES (?,?)";
    $arrData = array(
      $this->sale_id,
      $this->type,
    );
    $request = $this->insert_company($query_insert, $arrData, $this->db_company);


    return $request;
  }
}
