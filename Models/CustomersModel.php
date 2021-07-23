<?php
class CustomersModel extends Mysql
{
  public $id;
  public $document;
  public $name;
  public $address;
  public $phone;
  public $mobile;
  public $email;
  public $time_limit;
  public $date;
  public $status;

  public $db_company;

  public function __construct()
  {
    parent::__construct();
  }

  public function selectCustomers(int $perPage, array $filter)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $filter['id'];
    $this->document = $filter['document'];
    $this->name = $filter['name'];
    $this->address = $filter['address'];
    $this->phone = $filter['phone'];
    $this->mobile = $filter['mobile'];
    $this->email = $filter['email'];
    $this->date = $filter['date'];
    $this->status = $filter['status'];
    $this->perPage = $perPage;


    $date_range = ($this->date != '' && count($this->date) == 2) ?
      " AND u.created_at BETWEEN '{$this->date[0]} 00:00:00' AND '{$this->date[1]}  23:59:59'" : "";


    $where = "
      id LIKE '%$this->id%' AND
      document LIKE '%$this->document%' AND
      full_name LIKE '%$this->name%' AND
      address LIKE '%$this->address%' AND
      phone LIKE '%$this->phone%' AND
      mobile LIKE '%$this->mobile%' AND
      email LIKE '%$this->email%' AND
      status LIKE '%$this->status%' AND 
      status > 0 
      $date_range";

    $info = "SELECT COUNT(*) as count 
      FROM customers
      WHERE $where 
    ";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "
      SELECT *
      FROM customers
      WHERE $where  
      ORDER BY id DESC LIMIT 0, $this->perPage
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    return [
      'items' => $items,
      'dates' => $this->select_dates_company('created_at', 'customers', $this->db_company),
      'info' => $info_table
    ];
  }

  public function selectCustomer(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $sql = "SELECT * FROM customers WHERE id = $this->id";
    $request = $this->select_company($sql, $this->db_company);
    $shortName = explode(" ", $request['full_name']);
    $request['short_name'] = $shortName[0] . ' ' . $shortName[2];

    return $request;
  }

  public function insertCustomer(
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

    $this->document = $document;
    $this->name = $name;
    $this->address = $address;
    $this->phone = $phone;
    $this->mobile = $mobile;
    $this->email = $email;
    $this->time_limit = $time_limit;
    $this->status = $status;

    if ($this->name !== 'consumidor final' && $this->document !== '9999999999') {

      $sql = "SELECT * FROM customers WHERE full_name = '$this->name' OR document = '$this->document'";
      $request = $this->select_all_company($sql, $this->db_company);

      if (empty($request)) {
        $query_insert = "INSERT INTO customers (document, full_name, address, phone, mobile, email, time_limit, status) VALUES (?,?,?,?,?,?,?,?)";
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
        $request = $this->insert_company($query_insert, $arrData, $this->db_company);
      } else {
        $request = -2;
      }
    } else {
      $request = -4;
    }
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
