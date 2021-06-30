<?php
require_once('CompaniesModel.php');

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
    $this->id = $filter['id'];
    $this->tradename = $filter['tradename'];
    $this->city = $filter['city'];
    $this->status = $filter['status'];
    $this->date = $filter['date'];
    $this->perPage = $perPage;

    $date_range = "";
    if ($this->date != '' && count($this->date) == 2) {
      $date_range = '
        AND created_at BETWEEN "' . $this->date[0] . ' 00:00:00" AND "' . $this->date[1] . ' 23:59:59"
        OR created_at BETWEEN "' . $this->date[1] . ' 00:00:00" AND "' . $this->date[0] . ' 23:59:59"';
    }

    $where = '
      id LIKE "%' . $this->id . '%" AND
      tradename LIKE "%' . $this->tradename . '%" AND 
      city LIKE "%' . $this->city . '%" AND 
      status LIKE "%' . $this->status . '%" AND 
      status > 0
      ' . $date_range . '
    ';

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
    }
    return [
      'items' => $items,
      'info' => $info_table,
    ];
  }

  public function selectEstablishment(int $id)
  {
    $this->id = $id;
    $sql = "SELECT * FROM establishments WHERE id = $this->id";
    $request = $this->select($sql);
    $request['company'] = $this->Company->selectCompany($request['company_id']);
    return $request;
  }
}
