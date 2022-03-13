<?php
class PaymentMethodsModel extends Mysql
{
  public $id;
  public $status;

  public $db_company;

  public function __construct()
  {
    parent::__construct();
  }

  public function selectPaymentMethods(array $filter)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $filter['id'];
    $this->name = $filter['name'];
    $this->status = $filter['status'];

    $status = $this->status > 0 ? "status = $this->status AND" : '';


    $where = "
      id LIKE '%$this->id%' AND
      name LIKE '%$this->name%' AND
      $status
      status > 0 
    ";

    $info = "SELECT COUNT(*) as count 
      FROM payment_methods
      WHERE $where 
    ";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "SELECT *
      FROM payment_methods
      WHERE $where  
      ORDER BY id ASC
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    return [
      'items' => $items,
      'info' => $info_table
    ];
  }

  public function selectPaymentMethod(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $sql = "SELECT * FROM payment_methods WHERE id = $this->id";
    $request = $this->select_company($sql, $this->db_company);

    return $request;
  }

  public function updatePaymentMethod(
    int $id,
    int $status
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $this->status = $status;

    $sql = "UPDATE payment_methods SET status = ? WHERE id = $this->id";
    $arrData = array(
      $this->status
    );

    $request = $this->update_company($sql, $arrData, $this->db_company);

    return $request;
  }
}
