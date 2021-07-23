<?php
require_once('SystemModel.php');
class CompaniesModel extends Mysql
{
  public $id;
  public $ruc;
  public $names;
  public $tradename;
  public $representative;
  public $document;
  public $phone;
  public $email;
  public $type_trade_id;
  public $accounting;
  public $withholding_agent;
  public $microenterprise_taxpayer;
  public $data_base_id;
  public $plan_id;
  public $status;
  public function __construct()
  {
    parent::__construct();
    $this->System = new SystemModel;
  }

  public function selectCompanies(int $perPage, array $filter)
  {
    $this->id = $filter['id'];
    $this->ruc = $filter['ruc'];
    $this->names = $filter['names'];
    $this->tradename = $filter['tradename'];
    $this->representative = $filter['representative'];
    $this->document = $filter['document'];
    $this->phone = $filter['phone'];
    $this->email = $filter['email'];
    $this->type_trade_id = $filter['type_trade_id'];
    $this->accounting = $filter['accounting'];
    $this->withholding_agent = $filter['withholding_agent'];
    $this->microenterprise_taxpayer = $filter['microenterprise_taxpayer'];
    $this->data_base_id = $filter['data_base_id'];
    $this->plan_id = $filter['plan_id'];
    $this->status = $filter['status'];
    $this->perPage = $perPage;

    $date_range = ($this->date != '' && count($this->date) == 2) ?
      " AND created_at BETWEEN '{$this->date[0]} 00:00:00' AND '{$this->date[1]}  23:59:59'" : "";

    $where = "
      id LIKE '%$this->id%' AND
      ruc LIKE '%$this->ruc%' AND
      names LIKE '%$this->names%' AND
      tradename LIKE '%$this->tradename%' AND
      representative LIKE '%$this->representative%' AND
      document LIKE '%$this->document%' AND
      phone LIKE '%$this->phone%' AND
      email LIKE '%$this->email%' AND
      type_trade_id LIKE '%$this->type_trade_id%' AND
      accounting LIKE '%$this->accounting%' AND
      withholding_agent LIKE '%$this->withholding_agent%' AND
      microenterprise_taxpayer LIKE '%$this->microenterprise_taxpayer%' AND
      data_base_id LIKE '%$this->data_base_id%' AND
      plan_id LIKE '%$this->plan_id%' AND
      status LIKE '%$this->status%' AND
      status > 0
      $date_range
    ";

    $info = "SELECT COUNT(*) as count FROM companies WHERE $where ";
    $info_table = $this->info_table($info);

    $rows = "
      SELECT * 
      FROM companies 
      WHERE $where  
      ORDER BY id DESC LIMIT 0, $this->perPage
    ";

    $items = $this->select_all($rows);

    for ($i = 0; $i < count($items); $i++) {
      $items[$i]['data_base'] = $this->System->selectDatabase($items[$i]['data_base_id']);
      $items[$i]['type_trade'] = $this->System->selectTypetrade($items[$i]['type_trade_id']);
    }
    return [
      'items' => $items,
      'dates' => $this->select_dates('created_at', 'companies'),
      'info' => $info_table,
    ];
  }

  public function selectCompany(int $id)
  {
    $this->id = $id;
    $sql = "SELECT * FROM companies WHERE id = $this->id";
    $request = $this->select($sql);
    $request['data_base'] = $this->System->selectDatabase($request['data_base_id']);
    $request['type_trade'] = $this->System->selectTypetrade($request['type_trade_id']);
    return $request;
  }
}
