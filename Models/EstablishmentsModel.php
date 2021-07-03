<?php
require_once('CompaniesModel.php');
require_once('UsersModel.php');

class EstablishmentsModel extends Mysql
{
  public $id;
  public $company_id;
  public $n_establishment;
  public $tradename;
  public $province;
  public $city;
  public $parish;
  public $address;
  public $phone;
  public $status;

  public function __construct()
  {
    parent::__construct();
    $this->Company = new CompaniesModel;
  }


  public function selectEstablishments(int $perPage, array $filter)
  {

    $this->n_establishment = $filter['n_establishment'];
    $this->tradename = $filter['tradename'];
    $this->city = $filter['city'];
    $this->status = $filter['status'];
    $this->perPage = $perPage;

    $where = '
      n_establishment LIKE "%' . $this->n_establishment . '%" AND
      tradename LIKE "%' . $this->tradename . '%" AND 
      city LIKE "%' . $this->city . '%" AND 
      status LIKE "%' . $this->status . '%" AND 
      status > 0 AND
      company_id = ' . $_SESSION['userData']['establishment']['company_id'];

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
      $items[$i]['n_establishment'] = str_pad($items[$i]['n_establishment'], 3, "0", STR_PAD_LEFT);
      $items[$i]['company'] = $this->Company->selectCompany($items[$i]['company_id']);
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
    $request['n_establishment'] = str_pad($request['n_establishment'], 3, "0", STR_PAD_LEFT);
    $request['company'] = $this->Company->selectCompany($request['company_id']);

    return $request;
  }
}
