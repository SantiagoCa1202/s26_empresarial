<?php
class DevicesModel extends Mysql
{
  public $id;
  public $name;
  public $ip_adress;
  public $establishment_id;
  public $printer_name;
  public $status;

  public $db_company;

  public function __construct()
  {
    parent::__construct();
  }

  public function selectDevices(int $perPage, array $filter)
  {
    $this->db_company = "db_0604848929001";

    $this->name = $filter['name'];
    $this->ip_adress = $filter['ip_adress'];
    $this->establishment_id = $filter['establishment_id'];
    $this->status = $filter['status'];
    $this->perPage = $perPage;



    $where = "
      name LIKE '%$this->name%' AND
      ip_adress LIKE '%$this->ip_adress%' AND
      establishment_id LIKE '%$this->establishment_id%' AND
      status LIKE '%$this->status%' AND 
      status > 0";

    $info = "SELECT COUNT(*) as count 
      FROM devices
      WHERE $where 
    ";
    $info_table = $this->info_table($info);

    $rows = "
      SELECT *
      FROM devices
      WHERE $where  
      ORDER BY id DESC LIMIT 0, $this->perPage
    ";

    $items = $this->select_all($rows);

    return [
      'items' => $items,
      'info' => $info_table
    ];
  }

  public function searchDevice(string $ip_adress)
  {

    $this->ip_adress = $ip_adress;
    $sql = "SELECT * FROM devices WHERE ip_adress = '$this->ip_adress'";
    $request = $this->select($sql);

    return $request;
  }
}
