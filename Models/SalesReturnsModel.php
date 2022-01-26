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
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->sale_id = $sale_id;

    $query_insert = "INSERT INTO sales_return (sale_id) VALUES (?)";
    $arrData = array(
      $this->sale_id,
    );
    $request = $this->insert_company($query_insert, $arrData, $this->db_company);


    return $request;
  }

  public function updateCustomer(
    int $id,
    string $document,
    string $name,
    string $address,
    string $phone,
    string $mobile,
    string $email,
    string $time_limit,
    int $status
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $this->document = $document;
    $this->name = $name;
    $this->address = $address;
    $this->phone = $phone;
    $this->mobile = $mobile;
    $this->email = $email;
    $this->time_limit = $time_limit;
    $this->status = $status;

    if (
      $this->name !== 'consumidor final' && $this->document !== '9999999999' && $this->id !== 1
    ) {

      $sql = "SELECT * FROM customers WHERE id != '$this->id' AND (full_name = '$this->name' OR document = '$this->document')";
      $request = $this->select_all_company($sql, $this->db_company);

      if (empty($request)) {

        $sql = "UPDATE customers SET document = ?, full_name = ?, address = ?,  phone = ?, mobile = ?, email = ?, time_limit = ?, status = ? WHERE id = $this->id";
        $arrData = array(
          $this->document,
          $this->name,
          $this->address,
          $this->phone,
          $this->mobile,
          $this->email,
          $this->time_limit,
          $this->status
        );

        $request = $this->update_company($sql, $arrData, $this->db_company);
      } else {
        $request = -2;
      }
    } else {
      $request = -4;
    }
    return $request;
  }

  public function deleteCustomer(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    if ($this->id !== 1) {
      $sql = "UPDATE customers SET status = 0 WHERE id = $this->id";
      $request = $this->delete_company($sql, $this->db_company);
    } else {
      $request = -4;
    }
    return $request;
  }
}
