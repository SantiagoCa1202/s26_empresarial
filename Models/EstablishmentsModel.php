<?php
require_once('CompaniesModel.php');
require_once('UsersModel.php');
require_once('SystemModel.php');

class EstablishmentsModel extends Mysql
{
  public $id;
  public $company_id;
  public $n_establishment;
  public $tradename;
  public $province;
  public $city_id;
  public $parish;
  public $address;
  public $phone;
  public $status;

  public function __construct()
  {
    parent::__construct();
    $this->Company = new CompaniesModel;
    $this->System = new SystemModel;
  }


  public function selectEstablishments(int $perPage, array $filter)
  {

    $this->n_establishment = $filter['n_establishment'];
    $this->tradename = $filter['tradename'];
    $this->city_id = $filter['city_id'];
    $this->status = $filter['status'];
    $this->perPage = $perPage;
    $this->company_id = $_SESSION['userData']['establishment']['company_id'];

    $status = $this->status > 0 ? "status = $this->status AND" : '';


    $where = "
      n_establishment LIKE '%$this->n_establishment%' AND
      tradename LIKE '%$this->tradename%' AND 
      city_id LIKE '%$this->city_id%' AND 
      $status 
      status > 0 AND
      company_id = $this->company_id
    ";

    $info = "SELECT COUNT(*) as count FROM establishments WHERE $where ";
    $info_table = $this->info_table($info);

    $rows = "
      SELECT * 
      FROM establishments
      WHERE $where  
      ORDER BY id DESC LIMIT 0, $this->perPage
    ";

    $items = $this->select_all($rows);

    for ($i = 0; $i < count($items); $i++) {
      $items[$i]['company'] = $this->Company->selectCompany($items[$i]['company_id']);
      $items[$i]['city'] = $this->System->selectCity($items[$i]['city_id']);
    }
    return [
      'items' => $items,
      'info' => $info_table,
    ];
  }

  public function selectEstablishment(int $id)
  {

    $this->id = $id;


    $sql = "SELECT *,  e.id as id, e.phone as phone, u.name, u.last_name FROM establishments e
    JOIN users u
    ON e.executive_id = u.id
    WHERE e.id = $this->id";
    $request = $this->select($sql);
    $fil = [
      'name' => !empty($_GET['name']) ? strClean($_GET['name']) : '',
      'ip_adress' => !empty($_GET['ip_adress']) ? strClean($_GET['ip_adress']) : '',
      'establishment_id' => $request['id'],
      'status' => !empty($_GET['status']) ? intval($_GET['status']) : '',
    ];
    $request['company'] = $this->Company->selectCompany($request['company_id']);
    $request['city'] = $this->System->selectCity($request['city_id']);

    return $request;
  }
}
